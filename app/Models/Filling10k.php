<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filling10k extends Model
{
    use HasFactory;

    protected $table = 'filling10ks';

    protected $fillable = [
        'income',
        'cashflows',
        'balance_sheet',
        'shareholders_equity'
    ];
}
