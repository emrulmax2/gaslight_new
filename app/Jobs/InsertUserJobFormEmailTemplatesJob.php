<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\JobFormBaseEmailTemplate;
use App\Models\JobFormEmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InsertUserJobFormEmailTemplatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all default templates
        $templates = JobFormBaseEmailTemplate::all();
        foreach ($templates as $template) {
            JobFormEmailTemplate::create([
                'user_id' => $this->user->id,
                'job_form_id' => $template->job_form_id,
                'subject' => $template->subject,
                'content' => $template->content,
                'cc_email_address' => null,
                
                'created_by' => $this->user->id,
                'updated_by' => $this->user->id,
            ]);
        }

        Log::info("Inserted email templates for user: {$this->user->email}");
    }
}
