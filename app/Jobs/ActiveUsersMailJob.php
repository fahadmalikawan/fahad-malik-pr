<?php

namespace App\Jobs;

use App\Helpers\ActiveUsersMailHelper;
use App\Mail\ActiveUsersMail;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ActiveUsersMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new ActiveUsersMail($this->user));
        // ActiveUsersMailHelper::mailActiveUsers();
    }

    public function failed()
    {
        Test::create(['body' => 'Failed Job on user '.$this->user->email.' at '.Carbon::now()]);
        dispatch(new GeneralMailJob('Exception Notification', 'Admin', 'Failed Email Attempt', 'Email to user '.$this->user->name . ' is failed to be dispatched'));
    }
}
