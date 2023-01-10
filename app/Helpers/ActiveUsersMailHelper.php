<?php

namespace App\Helpers;

use App\Jobs\ActiveUsersMailJob;
use App\Mail\ActiveUsersMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ActiveUsersMailHelper
{
    public static function mailActiveUsers()
    {
        $users = User::where('is_active', true)->get();

        foreach ($users as $user) {
            dispatch(new ActiveUsersMailJob($user));
            // Mail::to($user->email)->send(new ActiveUsersMail($user));
        }

        // return response()->json(['success' => 'Emails sent successfully.']);
        return response()->json(['success' => 'Emails queued to be sent.']);
    }
}
