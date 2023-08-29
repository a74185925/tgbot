<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\City;
use App\Models\District;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Loot;

class PayController extends Controller
{
    public function test(Request $request) {
        $apironeID = config('apirone.id');
        $apironeKey = config('apirone.key');
        $apirone_url = config('apirone.url');
        $crypto_cur = 'ltc'; //крипто манета
        $fiat_cur = config('apirone.fiat');; // валюта счета фиат
        
        $exchang_rates = Http::get($apirone_url.'/ticker?currency='.$crypto_cur);
        if ($exchang_rates->failed()) {
            $message = json_decode($exchang_rates)->message;
            $result = ['status'=>'error', 'message'=>$message];
            
        }
        $fiat_cur_rate = json_decode($exchang_rates)->$fiat_cur;
        $fiat_price = 1;  //Цена в фиатной валюте 
        $crypto_price = number_format($fiat_price/$fiat_cur_rate, 8, '.', '') * 100000000;
        
        $create_invoice_url = $apirone_url.'/accounts/'.$apironeID.'/invoices';
        $args = [
            "amount" => $crypto_price,
            "currency" => $crypto_cur,
            "lifetime" => 3600,
            "callback_url" => config('app.url').'/apirone/callback',
            "user-data"=> [
                "merchant" => "SHOP",
                "url" => config('telegram.bot_url'),
                "price" => [
                    "currency" => $fiat_cur,
                    "amount" => $fiat_price
                    ]
                ],
        ];
        
        $resp = Http::post($create_invoice_url, $args);
        if ($resp->failed()) {
            $result = ['status'=>'error', 'message'=>'create invoice faild'];
        }
        
        // $result = json_decode($resp->json());
        $result = $resp->json();

        return json_encode($result);
    }

    public function tests(Request $request) {
        $mytime = Carbon::now();
        echo $mytime->toDateTimeString();
        echo 'tests';
    }
}
