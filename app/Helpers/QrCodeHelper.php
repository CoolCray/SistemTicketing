<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeHelper
{
    public static function generateSvg($data)
    {
        return QrCode::size(200)->generate($data);
    }
}
