<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
    use HasFactory;
     protected $fillable = [
        'code', 
        'name',
        'description',
        'notes',
        'attachment',
        'mainId'
    ];
      protected $table='testcase';
}