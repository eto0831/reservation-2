<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AdminNotificationRequest;

class AdminNotificationController extends Controller
{
    public function showForm()
    {
        return view('admin.email-notification');
    }

    public function sendNotification(AdminNotificationRequest $request)
    {
        $target = $request->input('target');
        $subject = $request->input('subject');
        $messageContent = $request->input('message');

        // 宛先の絞り込み
        $query = User::query();
        if ($target !== 'all') {
            $query->role($target); // Laravel Permissionを使用
        }

        $users = $query->get();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new AdminNotificationMail($subject, $messageContent));
        }

        return redirect()->back()->with('success', 'メール送信が完了しました');
    }
}
