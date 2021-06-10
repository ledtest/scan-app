<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanProductType extends Model
{
    use HasFactory;

    protected $table = 'scanProductType';

    protected $fillable = [
        'user_id',
        'code',
        'box',
        'group',
        'status',
        'token'
    ];
}

