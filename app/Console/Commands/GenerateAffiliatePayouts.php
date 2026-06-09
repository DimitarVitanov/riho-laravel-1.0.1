<?php

namespace App\Console\Commands;

use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateAffiliatePayouts extends Command
{
    protected $signature = 'affiliate:payouts-generate';
    protected $description = 'Generate monthly affiliate payouts for resellers with balance >= $10';

    public function handle(): int
    {
        $minimumPayout = 10.00;
        $payoutMonth = now()->format('Y-m');

        $resellers = User::where('is_reseller_enabled', true)->get();

        $generated = 0;

        foreach ($resellers as $reseller) {
            $approvedBalance = AffiliateCommission::where('reseller_user_id', $reseller->id)
                ->where('commission_status', 'approved')
                ->sum('commission_amount');

            if ($approvedBalance < $minimumPayout) {
                continue;
            }

            $existingPayout = AffiliatePayout::where('reseller_user_id', $reseller->id)
                ->where('payout_batch_month', $payoutMonth)
                ->first();

            if ($existingPayout) {
                continue;
            }

            DB::transaction(function () use ($reseller, $approvedBalance, $payoutMonth) {
                AffiliatePayout::create([
                    'reseller_user_id' => $reseller->id,
                    'payout_batch_month' => $payoutMonth,
                    'amount' => $approvedBalance,
                    'currency' => 'EUR',
                    'payout_method' => 'stripe',
                    'payout_status' => 'requested',
                    'minimum_payout_reached' => true,
                    'scheduled_for' => now()->startOfMonth()->addMonth(),
                ]);

                AffiliateCommission::where('reseller_user_id', $reseller->id)
                    ->where('commission_status', 'approved')
                    ->update(['commission_status' => 'payable']);
            });

            $generated++;
        }

        $this->info("Generated {$generated} affiliate payout requests.");

        return Command::SUCCESS;
    }
}
