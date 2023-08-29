<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loot extends Model
{
    use HasFactory;

    protected $table = 'loots';

    protected $fillable = [
        'product',
        'status',
        'manager',
        'img',
        'text',
        'whoupdate',
    ];

}
