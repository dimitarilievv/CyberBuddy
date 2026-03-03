<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//Route::get('/test-ai', function () {
//    // 1. Провери дали клучот се чита
//    $key = config('services.gemini.api_key');
//
//    if (empty($key)) {
//        return 'ПРОБЛЕМ: API клучот е празен! Провери го .env фајлот.';
//    }
//
//    // 2. Испрати барање покажи го целиот одговор
//    $response = \Illuminate\Support\Facades\Http::post(
//        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$key}",
//        [
//            'contents' => [
//                [
//                    'parts' => [
//                        ['text' => 'Кажи ми колку денови има неделата.']
//                    ]
//                ]
//            ]
//        ]
//    );
//
//    return [
//        'status' => $response->status(),
//        'body' => $response->json(),
//    ];
//});


require __DIR__.'/auth.php';
