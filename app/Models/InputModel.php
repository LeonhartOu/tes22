<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inputModel extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql';
    protected $table = 'input_models';

    protected $fillable = [
        'input1', 
        'input2', 
        'matched_percentage'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
