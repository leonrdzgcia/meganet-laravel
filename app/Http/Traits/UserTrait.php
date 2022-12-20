<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

trait UserTrait {
    public function userAutenticated (){
        return Auth::user();
    }

    public function userNotification(){
        $user = $this->userAutenticated();
        $notifications = [];
        foreach ($user->unreadNotifications as $notification) {
            $data = $notification->data[0];
            $notifications[] = [
                'id' => $notification->id,
                'reported_by' => $data['reporter'],
                'topic' => $data['topic'],
                'created_at' => Carbon::parse($data['created_at'])->diffForHumans()
            ];
            $this->data['notifications'] = $notifications;
        }
        return $notifications;
    }
}
