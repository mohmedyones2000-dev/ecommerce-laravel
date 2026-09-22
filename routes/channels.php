<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// ✅ قناة خاصة لكل مستخدم
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// ✅ قناة الإدارة (للمشرفين والمديرين فقط)
Broadcast::channel('admin.notifications', function ($user) {
    return in_array($user->role, ['admin', 'manager']);
});