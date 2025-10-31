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

        $subject = 'Welcome to GasEngineerApp.co.uk';

        $content = '<p>Dear <strong>'.$event->user->name.'</strong>,</p>
            <p>
                Welcome to <strong style="color: #0b4b6f;">GasEngineerApp.co.uk</strong>, a service provided by <strong>Engineer App Ltd</strong>. 
                We are delighted to have you on board.
                </p>
            <p>Our platform is designed to support professional gas engineers by providing tools 
                for managing gas safety certificates, scheduling appointments, and maintaining compliance 
                with ease and efficiency. We aim to help you simplify operations while maintaining the highest 
                standards of service.
            </p>
            <p>
                For any queries or assistance, our dedicated support team is available to help you at any time. 
                Please contact us at 
                <a href="mailto:support@gasengineerapp.co.uk" style="color: #0b4b6f;">support@gasengineerapp.co.uk</a> 
                or call <a href="tel:+442072479007" style="color: #0b4b6f;">0207 247 9007</a>.</p>
            <p>
                <a style="color: #FFF;" class="cta" href="https://www.gasengineerapp.co.uk" target="_blank" rel="noopener">
                    Visit GasEngineerApp.co.uk
                </a>
            </p>
            <p>Thank you for choosing <strong>Engineer App Ltd</strong>. We look forward to building a productive and lasting relationship with you.</p>
            <p>Kind regards,<br><strong>The Engineer App Team</strong></p>';

        GCEMailerJob::dispatch($configuration, [$event->user->email], new GCESendMail($subject, $content, []));
    }
}
