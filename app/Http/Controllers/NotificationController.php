<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\InAppNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    static function Notification($screen, $action, $route)
    {
        $user = User::find(Auth::user()->id);

        $user->notify(
            new InAppNotification(
                $screen,
                $action,
                $screen . ' ' . $action,
                $route,
                Carbon::now('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s')
            )
        );
    }

    public function index()
    {
        return view('layouts.notification');
    }

    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();

        return response()->json(['status' => 'success']);
    }
}
