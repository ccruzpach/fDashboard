<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $table = 'industries';
    
    protected $fillable = [
        'industry_name',
        'sic_code'
    ];

    // public function sector()
    // {
    //     return $this->belongsTo(Sector::class);
    // }

    // public function classification()
    // {
    //     return $this->hasMany(Sic::class);
    // }

}
