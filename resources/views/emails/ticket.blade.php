<!DOCTYPE html>
<html>
<head>
    <title>Damar Bali School</title>
    <style>
        body { font-family: sans-serif; }
        .ticket { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        .header { background: #00b6feff; color: white; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .qr-section { text-align: center; margin-top: 20px; }
        .qr-code { margin: 10px; display: inline-block;}
        .qr-title { font-weight: bold; margin-bottom: 5px; font-size: 14px; }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="header">
            <h2>Damar Bali School - Informasi Tiket</h2>
        </div>
        
        <div style="padding: 20px;">
            <p>Dear {{ $customer->name }},</p>
            <p>Thank you for your order. Here are your e-tickets:</p>

            <p>Please present this email at the entrance.</p>
        </div>
    </div>
</body>
</html>
