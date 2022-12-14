<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'stock_symbol',
        'company_title'
    ];

    public function companyIndustry()
    {
        return $this->belongsTo(Cik::class);
    }
}
