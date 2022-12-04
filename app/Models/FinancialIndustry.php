<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialIndustry extends Model
{
    use HasFactory;

    protected $table = 'financial_industries';
    
    protected $fillable = [
        'sic_code', 'id', 'industry'];
}