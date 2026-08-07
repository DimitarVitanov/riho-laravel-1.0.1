<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\Profile;
use App\Services\Est8ads\BillingService;
use App\Services\Est8ads\Discovery\DiscoveryManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AnalysisController extends Controller
{
    /**
     * User-triggered "Refresh analysis": (re)runs internet discovery for the
     * signed-in member's active property moves. New listings analyze
     * automatically on save; this lets a member re-run the AI on demand from
     * the dashboard instead of the command line.
     */
    public function trigger(Request $request, DiscoveryManager $discovery, BillingService $billing): JsonResponse
    {
        $profile = Profile::where('user_id', $request->user()->id)->first();

        if (! $profile) {
            return response()->json(['message' => 'Add a property to your move before running analysis.'], 422);
        }

        if ($billing->awaitingFirstPayment($profile)) {
            return response()->json(['message' => 'Please complete your first payment to activate your workspace.'], 402);
        }

        $moves = $profile->propertyMoves()->whereIn('status', ['active', 'submitted'])->get();

        if ($moves->isEmpty()) {
            return response()->json(['message' => 'Add a property to your move before running analysis.'], 422);
        }

        $queued = 0;
        foreach ($moves as $move) {
            try {
                $queued += count($discovery->dispatch($move, 'user_refresh', $request->user()->id));
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        if ($queued === 0) {
            return response()->json(['message' => 'Analysis could not be started right now. Our team has been notified.'], 500);
        }

        return response()->json([
            'message' => 'AI analysis started — new matches will appear here shortly.',
            'queued' => $queued,
        ]);
    }
}
