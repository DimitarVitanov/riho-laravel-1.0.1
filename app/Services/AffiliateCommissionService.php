<?php

namespace App\Services;

use App\Models\AffiliateCommission;
use App\Models\AffiliateReferral;
use App\Models\User;

class AffiliateCommissionService
{
    public function createCommissionFromPayment(
        int $referredUserId,
        float $grossPaymentAmount,
        string $sourcePaymentType = 'subscription',
        ?int $sourcePaymentId = null,
        string $currency = 'EUR'
    ): ?AffiliateCommission {
        $referral = AffiliateReferral::where('converted_user_id', $referredUserId)
            ->whereIn('status', ['signed_up', 'active_client'])
            ->first();

        if (!$referral) {
            return null;
        }

        $commissionPercent = 10.00;
        $commissionAmount = round($grossPaymentAmount * ($commissionPercent / 100), 2);

        return AffiliateCommission::create([
            'reseller_user_id' => $referral->reseller_user_id,
            'referred_user_id' => $referredUserId,
            'source_payment_id' => $sourcePaymentId,
            'source_payment_type' => $sourcePaymentType,
            'gross_payment_amount' => $grossPaymentAmount,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'currency' => $currency,
            'commission_status' => 'pending',
            'earned_at' => now(),
        ]);
    }

    public function getAvailableBalance(int $resellerUserId): float
    {
        return AffiliateCommission::where('reseller_user_id', $resellerUserId)
            ->where('commission_status', 'approved')
            ->sum('commission_amount');
    }
}
