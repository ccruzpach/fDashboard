<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cik extends Model
{
    use HasFactory;

    protected $table = 'ciks';
    
    protected $fillable = [
        'cik_number'
    ];

    // public function classification()
    // {
    //     return $this->belongsTo(Sic::class);
    // }

    public function company()
    {
        return $this->hasMany(Company::class);
    }
}
