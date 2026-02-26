<?php
namespace App\Notifications;
use Illuminate\Auth\Notifications\ResetPassword;

class ApiResetPassword extends ResetPassword
{
    protected function resetUrl($notifiable)
    {
        return config('app.mobile_reset_url')
            . '?token=' . $this->token
            . '\&email=' . urlencode($notifiable->email);
    }
}