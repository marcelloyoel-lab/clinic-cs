<?php

use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post(
    '/midtrans/notification',
    [MidtransController::class, 'notification']
)->name('midtrans.notification');
// Route::match(['GET', 'POST'], '/midtrans/notification', function () {
//     Log::info('Notification route reached');
//     return response()->json(['ok' => true]);
// });
