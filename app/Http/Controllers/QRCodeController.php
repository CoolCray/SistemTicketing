<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function generate()
    {
        // Force the format to svg and cast to string explicitly
        $qr = (string) QrCode::size(200)->format('svg')->generate('https://www.google.com');
        return response()->json([
            'status' => "success",
            'qr' => $qr

        ], 200);
    }
}
