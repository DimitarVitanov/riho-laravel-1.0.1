<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:10000'],
        ]);

        ContactInquiry::create([
            'uuid' => (string) Str::uuid(),
            'status' => 'new',
            'source' => 'est8ads.com',
            'name' => $validated['full_name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 2000, ''),
            'consent_at' => now(),
            'metadata' => ['country' => $validated['country'], 'role' => $validated['role']],
        ]);

        return back()->with('est8ads_contact_success', 'Thank you. Your message has been sent to EST8ADS.');
    }
}
