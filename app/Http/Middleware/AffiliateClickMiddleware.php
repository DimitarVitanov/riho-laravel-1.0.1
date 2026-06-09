<?php

namespace App\Http\Middleware;

use App\Models\AffiliateReferral;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AffiliateClickMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $refCode = $request->query('ref');

        if ($refCode && !$request->cookie('referral_code')) {
            $reseller = User::where('referral_code', $refCode)
                ->where('is_reseller_enabled', true)
                ->first();

            if ($reseller) {
                AffiliateReferral::create([
                    'reseller_user_id' => $reseller->id,
                    'referral_code' => $refCode,
                    'clicked_ip_hash' => hash('sha256', $request->ip()),
                    'clicked_user_agent_hash' => hash('sha256', $request->userAgent() ?? ''),
                    'landing_url' => $request->fullUrl(),
                    'referrer_url' => $request->header('referer'),
                    'cookie_expires_at' => now()->addDays(180),
                    'status' => 'clicked',
                ]);

                $response = $next($request);

                return $response->withCookie(cookie('referral_code', $refCode, 60 * 24 * 180));
            }
        }

        return $next($request);
    }
}
