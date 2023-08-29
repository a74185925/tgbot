<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'orders';

    protected $fillable = [
        'customer',
        'status',
        'city',
        'city_id',
        'district',
        'district_id',
        'product_name',
        'product',
        'price',
        'pay_data',
        'created_at',
        'updated_at'
    ];
}
