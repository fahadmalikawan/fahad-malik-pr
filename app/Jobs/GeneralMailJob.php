<?php

namespace App\Jobs;

use App\Mail\GeneralMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GeneralMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subject;
    private $title;
    private $target_name;
    private $body;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $title, $target_name, $body)
    {
        $this->subject = $subject;
        $this->title = $title;
        $this->target_name = $target_name;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to(env('SELF_TARGET_EMAIL'))->send(new GeneralMail($this->subject, $this->title, $this->target_name, $this->body));
    }
}
