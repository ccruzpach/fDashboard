<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyClassification extends Model
{
    use HasFactory;

    protected $table = 'company_classifications';
    
    protected $fillable = [
        'sic_number'];
}
