<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\Lead;
use App\Models\AiFeatureSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

/**
 * Public, cross-origin lead-capture endpoint (/lm/{agency}).
 *
 * The forms that hit this controller are embedded in pages served from other
 * origins (agency custom domains, est8ads.com, realestate.taxi, or static pages
 * pushed to the agency's own server). Because the browser will not send our
 * SameSite=lax session cookie on a cross-site POST, this endpoint runs WITHOUT
 * the session/CSRF middleware (see routes/web.php) — otherwise Laravel started a
 * brand-new session on every submit and the session-bound CSRF token could never
 * match. This controller therefore keeps NO session state: it renders responses
 * directly instead of using redirect()->back() flash/old(), and protects against
 * spam with a session-free honeypot plus Google reCAPTCHA.
 */
class LeadMagnetController extends Controller
{
    public function show(string $agency)
    {
        $agencyProfile = $this->resolveEnabledAgency($agency);

        return $this->renderForm($agencyProfile);
    }

    public function store(Request $request, string $agency)
    {
        $agencyProfile = $this->resolveEnabledAgency($agency);

        // Honeypot: real users never see or fill the "website" field; bots do.
        // Pretend success so bots get no signal, but store nothing.
        if (filled($request->input('website'))) {
            return $this->renderForm($agencyProfile, submitted: true);
        }

        // Google reCAPTCHA (session-free). Enforced only when configured so that
        // local/dev without keys — and any caller whose page predates the widget —
        // still falls back to the honeypot instead of silently dropping leads.
        if (!$this->passesRecaptcha($request)) {
            return $this->renderForm(
                $agencyProfile,
                errors: new MessageBag(['captcha' => __('Please confirm you are not a robot and try again.')]),
                old: $request->all(),
            );
        }

        // Validate form data - support both original and local SEO campaign forms
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'investor_type' => 'nullable|string|max:100',
            'interest_type' => 'nullable|string|max:100',
            'capital_range' => 'nullable|string|max:100',
            'buyer_profile' => 'nullable|string|max:100',
            'interest_amount' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:100',
            'campaign_id' => 'nullable|integer',
            'campaign_city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->renderForm(
                $agencyProfile,
                errors: $validator->errors(),
                old: $request->all(),
            );
        }

        // Parse full_name into first/last if provided
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        if ($request->full_name && !$firstName) {
            $nameParts = explode(' ', $request->full_name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
        }

        // Build message with additional context
        $message = $request->message ?? '';
        if ($request->interest_type) {
            $message = "Interest: {$request->interest_type}\n" . $message;
        }
        if ($request->capital_range) {
            $message = "Capital: {$request->capital_range}\n" . $message;
        }
        if ($request->buyer_profile) {
            $message = "Profile: {$request->buyer_profile}\n" . $message;
        }
        if ($request->campaign_city) {
            $message = "City: {$request->campaign_city}\n" . $message;
        }

        // Create lead
        $lead = Lead::create([
            'agency_profile_id' => $agencyProfile->id,
            'source' => $request->source ?? 'invisible_lead_magnet',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'investor_type' => $request->investor_type ?? $request->buyer_profile,
            'interest_amount' => $request->interest_amount,
            'message' => trim($message),
            'landing_page_url' => $request->headers->get('referer') ?? $request->fullUrl(),
            'status' => 'new',
        ]);

        // TODO: Notify AI Employee about new lead (can be done via event/job)

        return $this->renderForm($agencyProfile, submitted: true);
    }

    /**
     * Resolve the agency by id/slug/name and ensure the lead magnet is enabled,
     * or 404. Shared by show() and store().
     */
    protected function resolveEnabledAgency(string $agency): AgencyProfile
    {
        $agencyProfile = AgencyProfile::find($agency)
            ?? AgencyProfile::where('slug', $agency)->first()
            ?? AgencyProfile::where('company_name', 'like', "%$agency%")->first();

        if (!$agencyProfile) {
            abort(404, 'Agency not found');
        }

        $featureSetting = AiFeatureSetting::where('agency_profile_id', $agencyProfile->id)
            ->where('feature_key', 'invisible_lead_magnet')
            ->first();

        if (!$featureSetting || !$featureSetting->is_enabled) {
            abort(404, 'Lead magnet not available');
        }

        return $agencyProfile;
    }

    /**
     * Render the standalone capture page. Passes $errors/$old/$submitted
     * explicitly because this endpoint has no session (no ShareErrorsFromSession,
     * no old() flash).
     */
    protected function renderForm(
        AgencyProfile $agencyProfile,
        ?MessageBag $errors = null,
        array $old = [],
        bool $submitted = false,
    ) {
        return view('frontend.lead-magnet', [
            'agencyProfile' => $agencyProfile,
            'errors' => $errors ?? new MessageBag(),
            'old' => $old,
            'submitted' => $submitted,
        ]);
    }

    /**
     * Decide whether a submission clears reCAPTCHA (session-free).
     *
     * reCAPTCHA v2 site keys are domain-restricted, so the checkbox widget only
     * renders reliably on our own host — not on the many agency custom domains
     * that embed this form. We therefore *require* reCAPTCHA for same-origin
     * submissions (the standalone /lm/{id} page on app.villabit.ai, where the
     * widget is present and valid) and let cross-origin embeds through on the
     * honeypot alone. A token sent from a cross-origin embed is still rejected if
     * it is present but invalid.
     *
     * Returns true (skips the check) when no secret is configured so local/dev
     * keeps working via the honeypot.
     */
    protected function passesRecaptcha(Request $request): bool
    {
        $secret = config('services.recaptcha.secret');

        if (empty($secret)) {
            return true;
        }

        $token = $request->input('g-recaptcha-response');

        // Cross-origin embed (agency domain): honeypot is the primary guard.
        // Only fail if a token was supplied but does not verify.
        if (!$this->isSameOriginRequest($request)) {
            return empty($token) ? true : $this->verifyRecaptchaToken($secret, $token, $request->ip());
        }

        // Same-origin standalone page: a valid token is mandatory.
        if (empty($token)) {
            return false;
        }

        return $this->verifyRecaptchaToken($secret, $token, $request->ip());
    }

    /**
     * Whether the request originates from our own host (the standalone capture
     * page) rather than a cross-origin embed. A missing Origin/Referer (e.g. a
     * direct scripted POST) is treated as same-origin so it must pass reCAPTCHA.
     */
    protected function isSameOriginRequest(Request $request): bool
    {
        $source = $request->headers->get('origin') ?: $request->headers->get('referer');

        if (empty($source)) {
            return true;
        }

        $sourceHost = parse_url($source, PHP_URL_HOST);
        $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?: $request->getHost();

        return strcasecmp((string) $sourceHost, (string) $appHost) === 0;
    }

    /**
     * Verify a reCAPTCHA token against Google's siteverify API. Fails open on a
     * network error so a reCAPTCHA outage never silently discards a real lead.
     */
    protected function verifyRecaptchaToken(string $secret, string $token, ?string $ip): bool
    {
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]);

            return (bool) $response->json('success', false);
        } catch (\Throwable $e) {
            report($e);

            return true;
        }
    }
}
