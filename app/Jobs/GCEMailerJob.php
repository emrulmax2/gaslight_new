<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;

class GCEMailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $configuration;
    public $to;
    public $mailable;
    public $bcc;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $configuration, array $to, Mailable $mailable, array $bcc = [])
    {
        $this->configuration = $configuration;
        $this->to = $to;
        $this->mailable = $mailable;
        $this->bcc = (!empty($bcc) ? $bcc : []);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailer = app()->makeWith('gce.mailer', $this->configuration);
        $mailer->to($this->to)->bcc($this->bcc)->send($this->mailable);
    }
}
