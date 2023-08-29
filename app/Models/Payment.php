<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

        /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'payments';

    protected $fillable = [
        'merch',
        'merch_transaction',
        'order_id',
        'status',
        'currency',
        'sum',
        'info'
    ];
}
