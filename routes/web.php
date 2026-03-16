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

Route::get('/test-booking', function (\Illuminate\Http\Request $request) {
    // Check if test data exists first, otherwise create to satisfy constraints properly
    $customer = \App\Models\Customer::firstOrCreate(['email'=>'test_email@example.com'],['name'=>'Test User','phone_number'=>'1234567890']); 
    
    $food = \App\Models\Food::first();
    if (!$food) $food = \App\Models\Food::create(['name'=>'Test Food','price'=>10, 'stock'=>100]);
    
    $drink = \App\Models\Drink::first();
    if (!$drink) $drink = \App\Models\Drink::create(['name'=>'Test Drink','price'=>10, 'stock'=>100]);
    
    $package = \App\Models\Package::first();
    if (!$package) $package = \App\Models\Package::create(['name'=>'Test Package','price'=>100,'category'=>'gold', 'food_id' => $food->id, 'drink_id' => $drink->id, 'total_seats' => 1]); 
    
    $seat = \App\Models\Seat::first();
    if (!$seat) $seat = \App\Models\Seat::create(['seat_number'=>'TEST-01','is_available'=>true]); 
    
    $req = new \Illuminate\Http\Request(); 
    $req->merge([
        'package_id'=>$package->id, 
        'seats'=>[$seat->id], 
        'customer'=>['name'=>'Test User', 'email'=>'test_email@example.com', 'phone_number'=>'1234567890']
    ]); 
    $controller = new \App\Http\Controllers\Api\TransactionsController(); 
    return response()->json($controller->store($req)->getData());
});

Route::view('/{any}', 'welcome')->where('any', '.*');
