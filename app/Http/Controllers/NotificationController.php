<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function readAndRedirect($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $targetUrl = $notification->data['url'] ?? null;
            if ($targetUrl && $targetUrl !== '#') {
                return redirect($targetUrl);
            }
            if (isset($notification->data['proposal_id'])) {
                return redirect()->route('dosen.detail_proposal', $notification->data['proposal_id']);
            }
            return redirect()->route('dashboard');
        }
        return back();
    }
}
