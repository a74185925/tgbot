<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;            // Сотрудники и администраторы
use App\Models\Customer;        // пользователь бота
use App\Models\City;            // город
use App\Models\District;        // район
use App\Models\Product;         // товарная позиции
use App\Models\Order;           // заказ
use App\Models\Loot;            // посылка
use App\Models\Payment;         // Платежи
use App\Models\BotMessages;     // Bot messages


class AdminController extends Controller
{
    public function index(Request $request) {
        // список посылок на главной, если менеджер или админ то видит все, если курьер только свои
        if ($request->user()->is_admin || $request->user()->is_manager) {
            $loots = Loot::where('status', '1')->paginate(15);
        } else {
            $loots = Loot::where('manager', $request->user()->id)->orderByDesc('id')->paginate(15);
        }
        if ($loots->count()) {
            $data['loots'] = $loots;
        }

        $data['user'] = $request->user();
        return view('cabinet.cabinet', $data);
    }

    public function castomers(Request $request) {
        $castomers = Customer::paginate(15);
        $data['castomers'] = $castomers;
        $data['user'] = $request->user();
        return view('cabinet.castomers', $data);
    }

    public function orders(Request $request) {
        $orders = Order::orderByDesc('id')->paginate(15);
        $data['orders'] = $orders;
        $data['user'] = $request->user();
        return view('cabinet.orders', $data);
    }

    
    public function products(Request $request) {
        $products = Product::orderByDesc('id')->paginate(15);
        $cities = City::all();
        

        $data['products'] = $products;
        $data['cities'] = $cities;
        $data['user'] = $request->user();
        return view('cabinet.products', $data);
    }

    public function product_add(Request $request) {

        if ($request->isMethod('post')) {
            $request->validate([
                'city' => 'required',
                'name' => 'required',
                'price' => 'required|numeric',
            ]);
            $product = new Product;
            $product->city = $request->city;
            $product->name = $request->name;
            $product->price = $request->price;
            if ($request->has('description')) {
                $product->description = $request->description;
            }
            $result = $product->save();
            if ($result) {
                return redirect()->route('products')->withSuccess('Товарная позиция добавлена!');
            } else {
                return redirect()->route('products')->withFail('Ошибка');
            }  

        } else {
            $cities = City::all();
            $data['cities'] = $cities;
            $data['user'] = $request->user();
            return view('cabinet.product-add', $data);
        }
    }

    public function loots(Request $request) {
        if ($request->user()->is_admin || $request->user()->is_manager) {
            $loots = Loot::orderByDesc('id')->paginate(15);
        } else {
            $loots = Loot::where('manager', $request->user()->id)->orderByDesc('id')->paginate(15);
        }

        $cities = City::all();
        $data['loots'] = $loots;
        $data['cities'] = $cities;
        $data['districts'] = District::all();
        $data['user'] = $request->user();
        return view('cabinet.loots', $data);
    }

    public function loot_add(Request $request) {

        if ($request->isMethod('post')) {

            $request->validate([
                'city' => 'required',
                'product' => 'required',
                'foto' => 'required|image|mimetypes:image/jpeg,image/png',
                'instruction' => 'required',
            ]);

            $fotoPath = $request->file('foto')->store('storage/loots/'.$request->user()->name);

            $loot = new Loot;
            $loot->product = $request->product;
            $loot->manager = $request->user()->id;
            $loot->img = $fotoPath;
            $loot->text = $request->instruction;
            if (isset($request->district)) {
                $loot->district = $request->district;
            }
            

            $result = $loot->save();
            if ($result) {
                return redirect()->route('loot-add')->withSuccess('Посылка успешно добавлена!');
            } else {
                return redirect()->route('loot-add')->withFail('Ошибка');
            }  


        } else {
            $products = Product::all();
            $prod_list = [];
            foreach ($products as $item) {
                $prod_list[$item->city][$item->id] = $item->name;
            }

            $districts = District::all();
            $districts_list = [];
            foreach ($districts as $item) {
                $districts_list[$item->city][$item->id] = $item->name;
            }

            $cities = City::all();
            $data['cities'] = $cities;
            $data['products'] = $products;
            $data['prod_list'] = $prod_list;
            $data['districts_list'] = $districts_list;
            $data['user'] = $request->user();
            return view('cabinet.loot-add', $data);
        }
        
    }

    public function payments(Request $request) {
        $payments = Payment::orderByDesc('id')->paginate(15);
        $data['payments'] = $payments;
        $data['user'] = $request->user();
        return view('cabinet.payments', $data);
    }


    public function wokers(Request $request) {
        $wokers = User::orderByDesc('id')->paginate(15);
        $data['wokers'] = $wokers;
        $data['user'] = $request->user();
        return view('cabinet.wokers', $data);
    }

    public function woker_add(Request $request) {

        if ($request->isMethod('post')) {


            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
    
            $woker = new User;
            $woker->name = $request->name;
            $woker->email = $request->email;
            $woker->password = Hash::make($request->password);
            if ($request->role == "manager") {
                $woker->is_manager = 1;
            } else {
                $woker->is_courier = 1;
            }
            
            $result = $woker->save();
            if ($result) {
                return redirect()->route('loot-add')->withSuccess('Сотрудник успешно добавлен!');
            } else {
                return redirect()->route('loot-add')->withFail('Ошибка');
            }  
        } else {
            $data['user'] = $request->user();
            return view('cabinet.woker-add', $data);
        }
        
    }

    public function woker_del(Request $request) {

        if ($request->isMethod('post')) {
            $Manager = $request->user();
            $forDel = User::find($request->forDel);
            if ($Manager->is_admin) {
                $result = $forDel->delete();
            } elseif ($Manager->is_manager && ! $forDel->is_manager && ! $forDel->is_admin ) {
                $result = $forDel->delete();
            } else {
                return redirect()->route('wokers')->withFail('У вас нет права удалять этого пользователя');
            }
            
            if ($result) {
                return redirect()->route('wokers')->withSuccess('Сотрудник успешно удален!');
            } else {
                return redirect()->route('wokers')->withFail('Ошибка');
            }  
        } else {
            if (isset($request->del)) {
                $data['forDel'] = User::find($request->del);
                $data['user'] = $request->user();
                return view('cabinet.woker-del', $data);                
            } else {
                return redirect()->route('cabinet');
            }
        }  
    }

    public function cities(Request $request) {
        if ($request->isMethod('post')) {


            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'alpha_num', 'string', 'max:255'],
            ]);
    
            $city = new City;
            $city->name = $request->name;
            $city->slug = $request->slug;
            
            $result = $city->save();
            if ($result) {
                return redirect()->route('cities')->withSuccess('Город успешно добавлен!');
            }
            return redirect()->route('cities')->withFail('Ошибка');  
        }

        $cities = City::paginate(15);
        $districts = District::all();
        $data['cities'] = $cities;
        $data['districts'] = $districts;
        $data['user'] = $request->user();
        return view('cabinet.cities', $data);
    }

    public function district_add(Request $request) {
        if ($request->isMethod('post')) {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'alpha_num', 'string', 'max:255', 'unique:districts'],
            ]);
    
            $distr = new District;
            $distr->name = $request->name;
            $distr->slug = $request->slug;
            $distr->city = $request->city;
            $result = $distr->save();
            if ($result) {
                return redirect()->route('cities')->withSuccess('Район успешно добавлен!');
            }
            return redirect()->route('cities')->withFail('Ошибка');  
        }

        if (isset($request->city)) {
            $request->validate([
                'city' => ['numeric'],
            ]);
            $data['city'] = City::find($request->city);
            $data['districts'] = District::where('city', $request->city)->get();
            $data['user'] = $request->user();
            return view('cabinet.district-add', $data);
        }
        
        return redirect()->route('cities');
        
        
    }

    public function bot_messages(Request $request) {

        if ($request->isMethod('post')) {

            $request->validate([
                'text' => ['required'],
            ]);
    
            $this_message = BotMessages::find($request->message_id);
            $this_message->text = $request->text;
            if (isset($request->text1)) {
                $this_message->text1 = $request->text1;
            }
            if (isset($request->text2)) {
                $this_message->text2 = $request->text2;
            }
            if (isset($request->text3)) {
                $this_message->text3 = $request->text3;
            }
            if (isset($request->text4)) {
                $this_message->text4 = $request->text4;
            }
            if (isset($request->text5)) {
                $this_message->text5 = $request->text5;
            }
            if (isset($request->text6)) {
                $this_message->text6 = $request->text6;
            }
            if (isset($request->text7)) {
                $this_message->text7 = $request->text7;
            }
            if (isset($request->text8)) {
                $this_message->text8 = $request->text8;
            }
            $result = $this_message->save();

            if ($result) {
                return redirect()->route('bot_messages')->withSuccess('Сообщение обновлено!');
            } else {
                return redirect()->route('bot_messages')->withFail('Ошибка');
            }            
 
        } else {
            $messages = BotMessages::all();
            if ($request->selected_message) {
                $message = $messages->find($request->selected_message);
                $data['message'] = $message;
            } 
            $data['messages'] = $messages;
            $data['user'] = $request->user();
            return view('cabinet.bot-messages', $data);
        }
        
    }
    
    public function loot_del(Request $request) {

        if ($request->isMethod('post')) {
            $Manager = $request->user();
            $forDel = Loot::find($request->lootForDel);
            if ($Manager->is_admin || $Manager->is_manager) {
                $result = $forDel->delete();
            } elseif ($Manager->is_courier && $forDel->manager == $Manager->id ) {
                $result = $forDel->delete();
            } else {
                return redirect()->route('loots')->withFail('У вас нет права удалять эту посылку');
            }
            
            if ($result) {
                return redirect()->route('loots')->withSuccess('Посылка успешно удалена!');
            } else {
                return redirect()->route('loots')->withFail('Ошибка удаления посылки');
            }  
        }
    }    

}
