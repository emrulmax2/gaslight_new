<?php

namespace App\Listeners;

use App\Models\Option;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsOnRegistered implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        $message = "New engineer joined: ".$user->name;
        try {
            $SMSEAGLE_SIM = Option::where('category', 'SITE_API')->where('name', 'SMSEAGLE_SIM')->pluck('value')->first() ?? '';
            $SMSEAGLE_API = Option::where('category', 'SITE_API')->where('name', 'SMSEAGLE_API')->pluck('value')->first() ?? getenv('SMSEAGLE_API');
            $response = Http::withHeaders([
                            'access-token' => $SMSEAGLE_API,
                            'Content-Type' => 'application/json',
                        ])->withoutVerifying()->withOptions([
                            "verify" => false
                        ])->post('https://79.171.153.104/api/v2/messages/sms', [
                            'to' => ['07931926852'],
                            'text' => $message
                        ]);
            Log::info("New engineer joined: ".$user->name);
        } catch (Exception $e) {
            Log::info("Error: ".$e->getMessage());
        }
    }
}
