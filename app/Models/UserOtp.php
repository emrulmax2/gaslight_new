<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'expire_at',
    ];

    public function sendSMS($receiverNumber){
        $message = "Login OTP is ".$this->otp;
        try {
            $response = Http::withHeaders([
                            'access-token' => getenv('SMSEAGLE_API'),
                            'Content-Type' => 'application/json',
                        ])->withoutVerifying()->withOptions([
                            "verify" => false
                        ])->post('https://79.171.153.104/api/v2/messages/sms', [
                            'to' => [$receiverNumber],
                            'text' => $message
                        ]);
            info('SMS Sent Successfully.');
            return ['success' => true, 'message' => 'SMS Sent Successfully.'];
        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
            return ['success' => false, 'message' => "Error: ". $e->getMessage()];
        }
    }
}
