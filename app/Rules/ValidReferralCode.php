<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\UserReferralCode;
use App\Models\UserReferred;

class ValidReferralCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $referral = UserReferralCode::where('code', $value)->where('active', 1)->first();

        if (!$referral) {
            $fail('The Referral code is not valid.');
            return;
        }

        if ($referral->is_global == 1) {
            if ($referral->expiry_date && now()->gt($referral->expiry_date)) {
                $fail('The Referral code is expired.');
                return;
            }

            if ($referral->max_no_of_use !== null) {
                $usedCount = UserReferred::where('user_referral_code_id', $referral->id)->count();
                if ($usedCount >= $referral->max_no_of_use) {
                    $fail('The Referral code has reached its maximum usage limit.');
                    return;
                }
            }
        }
    }
}
