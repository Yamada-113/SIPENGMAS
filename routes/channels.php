<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User; // Pastikan import Model User ada

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Izin untuk notifikasi user spesifik
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});