<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\City;
use App\Models\District;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Loot;

class BotController extends Controller
{

    public function index(Request $request)
	{
        $data = $request->All();
        $hr_line = '####################';

        if ($data) {

            $update_id = $data['update_id'] ?? '';
            $chat_id = $data['message']['chat']['id'] ?? '';
            $text = $data['message']['text'] ?? '';
            $customer_id = $data['message']['from']['id'] ?? FALSE;
            $nickname = $data['message']['from']['username'] ?? 'Незнакмец';
            $callback_query = $data['callback_query'] ?? '';


            $mmenu = $this->getKeyBoard([[["text" => "ГЛАВНАЯ"], ["text" => "ПОМОЩЬ"]]]);
            
            try {
                if ($customer_id) {
                    $customer = Customer::firstOrCreate([
                        'tg_id' => $customer_id,
                    ]);
                }
            } catch (\Throwable $th) {
                Storage::prepend('logs/log_customers.txt', '*********************** END***********************');
                Storage::prepend('logs/log_customers.txt', print_r($th, true));
                Storage::prepend('logs/log_customers.txt', '***********************'.$chat_id.'***********************');
            }



            
            if ($chat_id && $text ) {
                switch ($text) {
                    case ($text == "/start" || $text == "ГЛАВНАЯ"):
                        $ctest = $this->setMainMenu($mmenu, $chat_id);
                        
                        $city_menu = $this->getCityListMenu();

                        $resp_args = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text'=> "Добро пожаловать! \n" . $hr_line . "\nНаши контакты:  Telegram: " . config('telegram.support'),
                            'reply_markup' => $city_menu,
                        ];
                        $resp = $this->send_request('sendMessage', $resp_args);
                        break;
                    case "ПОМОЩЬ":
                        $resp_args = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text'=> "Сообщение раздела помощь!\nВторая строка сообщения",
                            'reply_markup' => $mmenu,
                        ];
                        $resp = $this->send_request('sendMessage', $resp_args);
                        break;
                    case "/test":

                        $resp_args = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text'=> "Привет, ".$nickname."!\nТвое тестирование удачно!",
                            'reply_markup' => $mmenu,
                        ];
                        $resp = $this->send_request('sendMessage', $resp_args);
                        try {
                            $ctest = $this->setMainMenu($mmenu, $chat_id);
                        } catch (\Throwable $th) {
                            $ctest = $th;
                        }
                        Storage::prepend('test22.txt', '*********************** END***********************');
                        Storage::prepend('test22.txt', print_r($ctest, true));
                        Storage::prepend('test22.txt', '***********************'.$chat_id.'***********************');
                        break;
                }

            } elseif ($callback_query) {

                $cq_data = $callback_query['data'];
                $cq_chat_id = $callback_query['message']['chat']['id'];

                $cq_data_arr = explode( '_', $cq_data );

                if (count($cq_data_arr) > 1) {
                    // $cq_data_arr больше 1 элемента
                    try {
                        if ($cq_data_arr[0]== 'city') {
                            // Если префикс city и данные правельные
                            // Выводит список городов!
                            $this_city = City::findOrFail($cq_data_arr[1]);
                            $districts = District::where('city', $this_city->id)->get();
                            if ($districts->count()>0) {
                                $districts_list = [];
                                foreach ($districts as $district) {
                                    $callback_data = 'distr_'.$this_city->slug.'_'.$district->id;
                                    $districts_list[] = ['text' => $district->name, 'callback_data' => $callback_data];
                                }
                                $districts_list = array_chunk($districts_list, 2);
                                $cq_final_keyboard = $this->getInlineKeyBoard($districts_list);
                                $cq_final_text = "Вы выбрали: ".$this_city->name."! \nВыберите район:";
  
                            } else {
                                $products = Product::where('city', $this_city->id)->get();
                                $products_list = [];
                                foreach ($products as $product) {
                                    $callback_text = $product->name.' '.$product->price;
                                    $callback_data = 'products_'.$this_city->id.'_'.'0'.'_'.$product->id;
                                    $products_list[] = [['text' => $callback_text, 'callback_data' => $callback_data]];
                                }
                                $cq_final_keyboard = $this->getInlineKeyBoard($products_list);
                                $cq_final_text = "Вы выбрали:\nГород: ".$this_city->name."\n Выбитите товар:";
                            }
        
                        } elseif ($cq_data_arr[0]== 'distr') { 
                            // Если префикс distr
                            // Выводит список Районов!
                            $this_city = City::where('slug', $cq_data_arr[1])->firstOrFail();
                            $this_distr = District::findOrFail($cq_data_arr[2]);
                            $products = Product::where('city', $this_city->id)->get();
                            $products_list = [];
                            foreach ($products as $product) {
                                $callback_text = $product->name.' '.$product->price;
                                $callback_data = 'products_'.$this_city->id.'_'.$cq_data_arr[2].'_'.$product->id;
                                $products_list[] = [['text' => $callback_text, 'callback_data' => $callback_data]];
                            }
                            $cq_final_keyboard = $this->getInlineKeyBoard($products_list);
                            $cq_final_text = "Вы выбрали:\nГород: ".$this_city->name."! \nрайон: $this_distr->name"."\n Выбетите товар:";
                        } elseif ($cq_data_arr[0]== 'products') {
                            // Если префикс products
                            // Выводит список продуктов
                            $this_city = City::findOrFail($cq_data_arr[1]);
                            $this_distr = ($cq_data_arr[2]) ? District::find($cq_data_arr[2]) : 0 ;
                            $this_product = Product::findOrFail($cq_data_arr[3]);

                            $rtext = "Ваш заказ:\nГород: ".$this_city->name;
                            if ($this_distr) {
                                $rtext .= "\nРайон: ".$this_distr->name;
                            }
                            $rtext .= "\nТовар: ".$this_product->name."\nЦена: ".$this_product->price.'руб.';
                            $rtext .= "\nВыберите способ оплаты: ";
                            
                            // rdata  order _ (id города) _  (id дистрикта или 0) _ (id продукта) _ ( добавдяется ниже: ЧЕМ ОПЛАЧИВАЮТ)
                            $rdata = 'order_'.$this_city->id.'_'.$cq_data_arr[2].'_'.$this_product->id;

                            // Варианты оплаты Apirone
                            $apirone_coins = config('apirone.coins');

                            $keyboard_items = [];
                            foreach ($apirone_coins as $slug => $name) {
                                $keyboard_items[] = ['text' => $name, 'callback_data' => $rdata.'_'.$slug];
                            }
                            $keyboard_items = array_chunk($keyboard_items, 2);
                            $keyboard_items[] = [['text' => "C начала", 'callback_data' => 'home_0_0']];

                            $cq_final_keyboard = $this->getInlineKeyBoard($keyboard_items);
                            $cq_final_text = $rtext;

                        } elseif ($cq_data_arr[0] == 'home') {
                            $cq_final_keyboard = $this->getCityListMenu();
                            $cq_final_text = "Выберите город:";
                        } elseif ($cq_data_arr[0] == 'order') { 

                            $this_city = City::findOrFail($cq_data_arr[1]);
                            $this_distr = ($cq_data_arr[2]) ? District::findOrFail($cq_data_arr[2]) : 0 ;
                            $this_product = Product::findOrFail($cq_data_arr[3]);

                            $order = new Order;
                            $order->customer = $callback_query['from']['id'];
                            $order->status = 'new';
                            $order->city = $this_city->name;
                            $order->city_id = $this_city->id;
                            if ($this_distr) {
                                $order->district = $this_distr->name;
                                $order->district_id = $this_distr->id;
                            }
                            $order->product_name = $this_product->name;
                            $order->product = $this_product->id;
                            $order->price = $this_product->price;
                            $order->save();

                            // PAY DATA
                            $apironeID = config('apirone.id');
                            $apironeKey = config('apirone.key');
                            $apirone_url = config('apirone.url');
                            $crypto_cur = $cq_data_arr[4]; //крипто мoнета
                            $fiat_cur = config('apirone.fiat'); // валюта счета фиат

                            $exchang_rates = Http::get($apirone_url.'/ticker?currency='.$crypto_cur);
                            if ($exchang_rates->failed()) {
                                $error_time = Carbon::now()->toDateTimeString();
                                
                                try {
                                    $log_data = $error_time.' ------- '.json_decode($exchang_rates)->message.' ------- cq_data = '.$cq_data;
                                    Storage::prepend('logs/bot_errors.log', print_r($log_data, true));
                                } catch (\Throwable $th) {
                                    $log_data = $error_time.' ------- '."Ошибка получения курсов валют";
                                    $log_data_2 = $error_time.' ------- '.$th->getMessage();
                                    Storage::prepend('logs/bot_errors.log', print_r($log_data_2, true));
                                    Storage::prepend('logs/bot_errors.log', print_r($log_data, true)); 
                                }
                                $cq_final_text = "Ошибка генерации инвойса;\n Недоступен сервис курсов валют;\n Попробуйте позже";
                                $cq_final_keyboard = $this->getInlineKeyBoard([[['text' => "C начала", 'callback_data' => 'home_0_0']]]);
                            } else {
                                $fiat_cur_rate = json_decode($exchang_rates)->$fiat_cur;
                                $fiat_price = $this_product->price;  //Цена в фиатной валюте 
                                $crypto_price_text = number_format($fiat_price/$fiat_cur_rate, 8, '.', ''); //цена в крипте для показа пользователю
                                $crypto_price = explode(".", $crypto_price_text)[1]; //цена в крипте для запроса
                                $create_invoice_url = $apirone_url.'/accounts/'.$apironeID.'/invoices';
                                $invoice_args = [
                                    "amount" => settype($crypto_price, "integer"),
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
                                $invoice_resp = Http::post($create_invoice_url, $invoice_args);
                                if ($invoice_resp->failed()) {

                                    $error_time = Carbon::now()->toDateTimeString();
                                    try {
                                        Storage::prepend('logs/bot_errors.log', print_r($invoice_resp->json(), true));
                                        Storage::prepend('logs/bot_errors.log', print_r('*****'.$error_time.'*****', true));
                                    } catch (\Throwable $th) {
                                        $log_data = $error_time.' ------- '."Ошибка получения invoce";
                                        $log_data_2 = $error_time.' ------- '.$th->getMessage();
                                        Storage::prepend('logs/bot_errors.log', print_r($log_data_2, true));
                                        Storage::prepend('logs/bot_errors.log', print_r($log_data, true)); 
                                    }
                                    $cq_final_text = "Ошибка генерации инвойса";
                                    $cq_final_keyboard = $this->getInlineKeyBoard([[['text' => "C начала", 'callback_data' => 'home_0_0']]]);
                                } else {
                                    $pay_method_name = config('apirone.coins')[$crypto_cur];
                                    $invoice = $invoice_resp->object();
                                    // Добавляем Payment в базу
                                    $payment = new Payment;
                                    $payment->merch = 'apirone';
                                    $payment->merch_transaction = $invoice->invoice;
                                    $payment->order_id = $order->id;
                                    $payment->status = 'new';
                                    $payment->currency = $crypto_cur;
                                    $payment->sum = $crypto_price;
                                    try {
                                        $invoice_url_property_name = 'invoice-url';
                                        $payment->info = json_encode([
                                            "address"     => $invoice->address,
                                            "invoice-url" => $invoice->{$invoice_url_property_name},
                                            "price-text"  => $crypto_price_text.' '.$crypto_cur

                                        ]);

                                    } catch (\Throwable $th) {
                                        Storage::prepend('logs/payment_add_errors.log', print_r($invoice, true));
                                        $payment->info = json_encode(["address"=>"NoN"]);
                                        $error_time = Carbon::now()->toDateTimeString();
                                        Storage::prepend('logs/payment_add_errors.log', print_r($error_time.' ------- '.$th->getMessage(), true)); 
                                    }
                                    
                                    $payment->save();
                                    // СООБЩЕНИЕ С ИНФОРМАЦИЕЙ ОБ ОПЛАТЕ
                                    $pay_address = $invoice->address;
                                    $rtext = "ID ЗАКАЗА: ".$order->id."\nВаш заказ:\nГород: ".$this_city->name;
                                    if ($this_distr) {
                                        $rtext .= "\nРайон: ".$this_distr->name;
                                    }
                                    $rtext .= "\nТовар: ".$this_product->name."\nЦена: ".$this_product->price.'руб.';
                                    $rtext .= "\nДля приобретения выбранного товара, оплатите\n<b>".$crypto_price_text."</b> ".$pay_method_name."\nНа адрес: \n<b>".$pay_address."</b>";

                                    // Формат (pay) (order id) (payment id)
                                    $rdata = 'pay_'.$order->id.'_'.$payment->id;
                                    $cq_final_keyboard = $this->getInlineKeyBoard([[
                                        ['text' => 'Проверить оплату', 'callback_data' => $rdata],
                                    ]]);
                                    $cq_final_text = $rtext;    
                                }
                            }                    

                        } elseif ($cq_data_arr[0] == 'pay') {
                            
                            $this_order = Order::findOrFail($cq_data_arr[1]);
                            $this_payment = Payment::findOrFail($cq_data_arr[2]);
                            // Проверка состояния платежа в базе

                            if ($this_order->status == 'completed') {
                                if ($this_order->loot) {
                                    $this_loot = Loot::findOrFail($this_order->loot);
                                    $loot_text = $this_loot->text;
                                    $loot_img_link = config('app.url').'/'.$this_loot->img;
                                    $cq_final_text = "Заказ ID: ".$this_order->id." оплачен!\n Информация по вашей покупке:\n";
                                    $cq_final_text .= $loot_text."\n".$loot_img_link;

                                } else {
                                    $cq_final_text = "Для получения информации по заказу ID: ".$this_order->id."\n Обратитесь в поддержку\n";
                                }
                                
                            } else {
                                // Запрос статуса инвойса
                                $invoice_status_url = "https://apirone.com/api/v2/accounts/".config('apirone.id')."/invoices/".$this_payment->merch_transaction;
                                $invoice_status_args = ['transfer-key'=>config('apirone.key')];
                                $invoice_status_resp = Http::get($invoice_status_url, $invoice_status_args);
                                if ($invoice_status_resp->failed()) {
                                    Storage::prepend('log/pay_chek.log', print_r($invoice_status_resp->json(), true));
                                    $cq_final_text = "Ошибка проверки платежа, обратитесь в поддержку";
                                    $cq_final_keyboard = $this->getInlineKeyBoard([[['text' => "Главная", 'callback_data' => 'home_0_0']]]);
                                } else {
                                    $invoice_status_obj = $invoice_status_resp->object();
                                    if ($invoice_status_obj->status == 'created') {
                                        $cq_final_text = "Оплата еще не поступила, пожалуйста совершите платеж на указанные реквизиты";
                                        $cq_final_keyboard = $this->getInlineKeyBoard([[
                                            ['text' => 'Проверить оплату', 'callback_data' => $cq_data],
                                        ]]);
                                    } elseif ($invoice_status_obj->status == 'paid' || $invoice_status_obj->status == 'overpaid') {
                                        $cq_final_text = "Оплата поступила, ожидаем подтверждение сети";
                                        $cq_final_keyboard = $this->getInlineKeyBoard([[
                                            ['text' => 'Проверить оплату', 'callback_data' => $cq_data],
                                        ]]);
                                    } elseif ($invoice_status_obj->status == 'completed') {
                                        $this_payment->status = 'completed';
                                        $this_payment->save();
                                        $this_order->status = 'completed';
                                        $this_order->save(); 
                                        try {
                                            if ($this_order->district_id) {
                                                $customer_loot = Loot::where('status', 1)->where('product', $this_order->product)->where('district', $this_order->district_id)->firstOrFail();
                                            } else {
                                                $customer_loot = Loot::where('status', 1)->where('product', $this_order->product)->firstOrFail();
                                            }
                                        } catch (\Throwable $th) {
                                            $error_time = Carbon::now()->toDateTimeString();
                                            $error_msg = $error_time.' ------- '.$th->getMessage();
                                            Storage::prepend('logs/customer_loot_errors.log', print_r($error_msg, true));
                                            $customer_loot = 0;
                                        }
                                        if ($customer_loot) {
                                            // Сохраняем посылку в заказе
                                            $this_order->loot = $customer_loot->id;
                                            $this_order->save();
                                            // Делаем посылку неактивной
                                            $customer_loot->status = 0;
                                            $customer_loot->save();
                                            // Отправляем юзеру инфо по посылке
                                            $loot_text = $customer_loot->text;
                                            $loot_img_link = config('app.url').'/'.$customer_loot->img;
                                            $cq_final_text = "Заказ ID: ".$this_order->id." оплачен!\n Информация по вашей покупке:\n";
                                            $cq_final_text .= $loot_text."\n".$loot_img_link;
                                        } else {
                                            $cq_final_text = "Заказ ID: ".$this_order->id." оплачен!\n За информацией по вашей покупке обратитесь в поддержку:\n";
                                        }

                                    } elseif ($invoice_status_obj->status == 'partpaid') {
                                        $cq_final_text = "Оплата поступила частично, пожалуйста доплатите разницу на указанные реквизиты";
                                    } else {
                                        $cq_final_text = 'Инвойс просрочен, создайте новый или обратитесь в поддержку';
                                        $cq_final_keyboard = $this->getInlineKeyBoard([[
                                            ['text' => 'Создать новый', 'callback_data' => 'home_0_0'],
                                        ]]);
                                    }                                 
                                }
                            }
                        }
                        // Отправляем результаты пользователю
                        if (isset($cq_final_text) && isset($cq_final_keyboard)) {
                            $cq_final_args = [
                                'chat_id' => $cq_chat_id,
                                'parse_mode' => 'HTML',
                                'text'=> $cq_final_text,
                                'reply_markup' => $cq_final_keyboard,
                            ];  
                        } elseif(isset($cq_final_text) && !isset($cq_final_keyboard)) {
                            $cq_final_args = [
                                'chat_id' => $cq_chat_id,
                                'parse_mode' => 'HTML',
                                'text'=> $cq_final_text,
                            ];  
                        }else {
                            $cq_final_args = [
                                'chat_id' => $cq_chat_id,
                                'parse_mode' => 'HTML',
                                'text'=> "В начало /start",
                            ];                             
                        }
                        $cq_final_resp = $this->send_request('sendMessage', $cq_final_args);
                    } catch (\Throwable $th) {
                        try {
                            $error_time = Carbon::now()->toDateTimeString();
                            $error_msg = $error_time.' ------- '.$th->getMessage();
                            if (isset($cq_data)) {
                                $error_msg .= ' ------- '.$cq_data;
                            }
                            $log_file_name = 'logs/bot_errors.log';
                        } catch (\Throwable $th2) {
                            $error_msg = $th2;
                            $log_file_name = 'logs/critical_errors.log';
                        }
                        Storage::prepend($log_file_name, print_r($error_msg, true));
                        $cq_final_text = "Непредвиденная ошибка";
                        $cq_final_keyboard = $this->getInlineKeyBoard([[['text' => "Вернуться на главную", 'callback_data' => 'home_0_0']]]);
                        $cq_final_args = [
                            'chat_id' => $cq_chat_id,
                            'parse_mode' => 'HTML',
                            'text'=> $cq_final_text,
                            'reply_markup' => $cq_final_keyboard,
                        ];  
                        $cq_final_resp = $this->send_request('sendMessage', $cq_final_args);
                    }
                }





            } else {
                Storage::prepend('attempt1.txt', '***********************'.$chat_id.'***********************');
                Storage::prepend('attempt1.txt', $update_id);
                Storage::prepend('attempt1.txt', $chat_id);
                Storage::prepend('attempt1.txt', $text);
                Storage::prepend('attempt1.txt', print_r($data, true));
                Storage::prepend('attempt1.txt', '***********************'.$chat_id.'***********************');                
            }
        }

        return 'ok';
    }

    public function setWebhook() {
        if ($url = config('telegram.webhookUrl')) {

            $setWebhookUrl = config('telegram.url') . config('telegram.token') . '/setWebhook?url=' . $url . config('telegram.webhookPath');

            $response = Http::get($setWebhookUrl);
            
            return $response;
        }
    }
    
    protected function send_request($method, $args = []) {

        $request_url = config('telegram.url') . config('telegram.token') . '/' . $method;

        $response = Http::post($request_url, $args );
        $result = [];
        if ($response->failed()) {
            $result['status'] = FALSE;
            try {
                $result['message'] = $response->json();
            } catch (\Throwable $th) {
                $result['message'] = 'unknown error';
            }
        } else {
            $result['status'] = TRUE;
            try {
                $result['message'] = $response->json();
            } catch (\Throwable $th) {
                $result['message'] = 'unknown message';
            }
        }

        return $result;
        
    }

    private function getInlineKeyBoard($data) {
        $inlineKeyboard = array(
            "inline_keyboard" => $data,
        );
        return json_encode($inlineKeyboard);
    }

    private function getKeyBoard($data){
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => false,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }

    protected function setMainMenu($menu, $chat_id) {
        $request_url = config('telegram.url') . config('telegram.token') . '/' . 'sendMessage';
        
        $args = [
            'chat_id' => $chat_id,
            'text'=> "menu",
            'reply_markup' => $menu,
        ];
        $resp = Http::post($request_url, $args );
        
        return $resp;
    }

    protected function getCityListMenu() {
        $sityes_list = [];
        foreach (City::all() as $city) {
            $sityes_list[] = ['text' => $city->name, 'callback_data' => 'city_'.$city->id];
        }
        $sityes_list = array_chunk($sityes_list, 2);
        $city_menu = $this->getInlineKeyBoard($sityes_list);

        return $city_menu;
    }
}
