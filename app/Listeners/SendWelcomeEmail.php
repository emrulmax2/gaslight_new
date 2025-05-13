<?php

namespace App\Listeners;

use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
{

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $configuration = [
            'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'smtp_port' => env('MAIL_PORT', '587'),
            'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
            'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
            'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            
            'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
            'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safety Engineer'),

        ];

        $subject = 'Welcome to Gas Safety Engineer, '.$event->user->name.'!';

        $content = 'Hi '.$event->user->name.',<br/><br/>';
        $content .= '<p>Welcome to Gas Safety Engineer — we\'re excited to have you on board!</p>';
        $content .= '<p>Your account has been successfully created, and you\'re all set to start exploring. Whether you\'re here to 
                     Create unlimited Certificates, Invoices and Quotes. Intigrated with QuickBooks, Manage your Customers, 
                     we\'re here to support you every step of the way.</p>';

        $content .= '<p>Thanks for joining us — let\'s make something awesome together!</p>';
        $content .= 'Warm regards<br/>';
        $content .= 'Gas Safety Engineer';

        GCEMailerJob::dispatch($configuration, [$event->user->email], new GCESendMail($subject, $content, []));
    }
}
