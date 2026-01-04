<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        // Mark all as read
        auth()->user()->unreadNotifications->markAsRead();

        return view('admin.notifications.index');
    }
}
