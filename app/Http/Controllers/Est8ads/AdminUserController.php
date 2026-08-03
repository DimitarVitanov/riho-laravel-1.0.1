<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Update the editable fields of an EST8ADS user from the admin panel's
     * Users table (name, email, phone, status). Administrator accounts are
     * managed from the main Villa Bit admin instead.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        abort_unless($user->has_est8ads_access, 404);
        abort_if($user->isAdmin(), 422, 'Administrator accounts cannot be edited here.');

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'suspended', 'waitlist'])],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => [
                'id' => 'U-' . $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->isAgency() ? 'Agency' : 'Private user',
                'status' => ucfirst($user->status),
                'moves' => $user->agencyProfile?->agencyListings()->count() ?? 0,
                'joined' => $user->created_at?->toDateString(),
            ],
        ]);
    }
}
