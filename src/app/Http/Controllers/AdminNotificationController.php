<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminNotificationController extends Controller
{
    public function showForm()
    {
        return view('admin.email-notification');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

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
