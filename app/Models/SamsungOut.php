<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamsungOut extends Model
{
    use HasFactory;

    protected $table = 'samsungOuts';

    protected $fillable = [
        'user_id',
        'product_type_id',
        'scan_order',
        'pn',
        'code',
        'box',
        'group',
        'lot',
        'qty',
        'dc',
        'printed_nums',
        'reprinted_nums',
        'is_confirmed',
        'token'
    ];
}
