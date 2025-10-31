<?php

namespace App\Listeners;

use App\Jobs\InsertUserJobFormEmailTemplatesJob;
use Illuminate\Auth\Events\Registered;

class InsertUserJobFormEmailTemplatesListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Dispatch the job
        InsertUserJobFormEmailTemplatesJob::dispatch($event->user);
    }
}
