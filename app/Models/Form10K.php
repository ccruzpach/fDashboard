<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form10K extends Model
{
    use HasFactory;

    protected $table = 'form10_k_s';

    protected $fillable = [
        'filling_date',
        'operations',
        'income',
        'cash_flows',
        'balance_sheet',
        'shareholders_equity'    
    ];

    
}
