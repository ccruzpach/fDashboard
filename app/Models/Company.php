<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    protected $table = 'companies';

    protected $fillable = [
        'cik_number',
        'sic_number',
        'stock_symbol',
        'company_title'];
}
