<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});
Route::get('/tests', [ PayController::class, 'tests' ]);
/*
|--------------------------------------------------------------------------
| Routes доступные всем авторизованным
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('cabinet');;
    })->name('dashboard');

    Route::get('sities', [ AdminController::class, 'sities' ]);
});
/*
|--------------------------------------------------------------------------
| Routes доступные ТОЛЬКО АДМИНУ
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:sanctum','isadmin']], function () {

    Route::get('/admin', function () { return 'admin'; });

});
/*
|--------------------------------------------------------------------------
| Routes доступные МЕНЕДЖЕРУ АДМИНУ
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:sanctum','ismanager']], function () {

    Route::get('/castomers', [ AdminController::class, 'castomers' ]);
    Route::get('/orders', [ AdminController::class, 'orders' ]);
    Route::get('/products', [ AdminController::class, 'products' ])->name('products');;
    Route::get('/payments', [ AdminController::class, 'payments' ]);
    Route::get('/wokers', [ AdminController::class, 'wokers' ])->name('wokers');
    // ADD ROUTES
    Route::match(['get', 'post'], 'woker-add', [ AdminController::class, 'woker_add' ]);
    Route::match(['get', 'post'], 'product-add', [ AdminController::class, 'product_add' ]);
    Route::match(['get', 'post'], 'cities', [ AdminController::class, 'cities' ])->name('cities');
    Route::match(['get', 'post'], 'district-add', [ AdminController::class, 'district_add' ]);
    Route::match(['get', 'post'], 'bot-messages', [ AdminController::class, 'bot_messages' ])->name('bot_messages');
    // DEL ROUTES
    Route::match(['get', 'post'], 'delete-woker', [ AdminController::class, 'woker_del' ]);

});
/*
|--------------------------------------------------------------------------
| Routes доступные КУРЬЕРУ МЕНЕДЖЕРУ АДМИНУ
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:sanctum','iscourier']], function () {

    Route::get('cabinet', [ AdminController::class, 'index' ])->name('cabinet');
    Route::get('loots', [ AdminController::class, 'loots' ])->name('loots');
    Route::match(['get', 'post'], 'loot-add', [ AdminController::class, 'loot_add' ])->name('loot-add');
    Route::post('loot-del', [ AdminController::class, 'loot_del' ])->name('loot-del');
    
});
/*
|--------------------------------------------------------------------------
| Routes для БОТА
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'bot', 'middleware' => 'web'], function () {
    Route::post('webhook', [ BotController::class, 'index' ]);
    // Route::get('webhook', [ BotController::class, 'index' ]);

    Route::get('setwebhook', [ BotController::class, 'setWebhook' ]);

});

/*
|--------------------------------------------------------------------------
| Routes Для apirone.com
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'apirone', 'middleware' => 'web'], function () {

    Route::match(['get', 'post'], 'test', [ PayController::class, 'test' ]);

});

