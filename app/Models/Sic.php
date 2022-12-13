<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sic extends Model
{
    use HasFactory;

    protected $table = 'sics';
    
    protected $fillable = [
        'sic_code',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
