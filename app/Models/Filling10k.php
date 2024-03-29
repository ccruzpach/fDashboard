<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filling10k extends Model
{
    use HasFactory;

    protected $table = 'filling10ks';

    protected $fillable = [
      'filling_content',
      'filling_date'
    ];
}
