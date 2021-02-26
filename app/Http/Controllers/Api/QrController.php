<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Qrcode as AppQrcode;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    

    public function generateQrCode(){
       
             $data['user_id'] = auth()->user()->id;
            $data['sku'] = bin2hex(random_bytes(24));
            $result = AppQrcode::create($data);

      
        return QrCode::size(250)->generate(bin2hex(random_bytes(24)));
    }
}
