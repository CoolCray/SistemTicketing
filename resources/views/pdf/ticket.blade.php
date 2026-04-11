<!DOCTYPE html>
<html>
<head>
    <title>Damar Bali School</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .page-break { page-break-after: always; }
        .ticket { border: 1px dashed #333; padding: 20px; margin-bottom: 30px; border-radius: 8px; }
        .header { background: #00b6feff; color: white; padding: 10px;text-align: center; }
        .details { margin-top: 20px; }
        .details p { margin: 5px 0;}
        .qr-container { width: 100%; margin-top: 20px; text-align: center;}
        .qr-box { display: block; width: 90%; margin: 20px auto; border: 1px solid #eee; padding: 20px; box-sizing: border-box; }
        .qr-title { font-weight: bold; margin-bottom: 15px; font-size: 18px; }
        .qr-image { width: 80%; margin: auto; }
    </style>
</head>
<body>
    @foreach($transactions as $index => $transaction)
    <div class="ticket">
        <div class="header">
            <h2>Damar Bali School Ticket</h2>
        </div>
        
        <div class="details">
            <h3>Ticket Information:</h3>
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Seat:</strong> {{ $transaction->seats ? $transaction->seats->seat_number : 'N/A' }}</p>
            <p><strong>Package:</strong> {{ $transaction->packages ? $transaction->packages->name : 'N/A' }}</p>
            <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y') }}</p>
        </div>
        
        <div class="qr-container gap-10">
            <div class="qr-box">
                <div class="qr-title">Check-in QR (Scan to Enter)</div>
                <div class="qr-image">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(App\Helpers\QrCodeHelper::generateSvg($transaction->checkin_token)) !!}" style="width: 100%; height: auto;">
                </div>
            </div>
            
            <div class="page-break"></div>
            
            <div class="qr-box mt-4">
                <div class="header" style="margin-bottom: 20px; background-color: orange;">
                    <h2>Damar Bali School Ticket - Check Out</h2>
                </div>
                <div class="qr-title">Check-out QR (Scan to Leave)</div>
                <div class="qr-image">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(App\Helpers\QrCodeHelper::generateSvg($transaction->checkout_token)) !!}" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        
        <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
            Please present this PDF or the QR code at the entrance.
        </p>
    </div>
    
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach
</body>
</html>
