<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Http\Controllers\QRCodeController;

Route::get('/', function () {
    return view('welcome');
})->name('login');


Route::get('/mail/send', function () {
    $data = [
        'subject' => 'Testing Kirim Email',
        'title' => 'Testing Kirim Email',
        'body' => 'Ini adalah email uji coba dari Tutorial Laravel: Send Email Via SMTP GMAIL @ qadrLabs.com'
    ];

    Mail::to('dwkiller831@gmail.com')->send(new SendEmail($data));
});

Route::get('/qr/generate', [QRCodeController::class, 'generate'])->name('qr.generate');

Route::view('/{any}', 'welcome')->where('any', '.*');
