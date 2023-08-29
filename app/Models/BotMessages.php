<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotMessages extends Model
{
    use HasFactory;

    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'bot_messages';

    protected $fillable = [
        'name',
        'text',
        'text2',
        'text3',
        'text4',
        'text5',
        'text6',
        'text7',
        'text8',
    ];
    public $timestamps = false;
}
