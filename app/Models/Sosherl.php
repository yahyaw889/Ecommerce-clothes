<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sosherl extends Model
{

    use HasFactory;
    protected $fillable =
    ['name','phone','email','sosherl','disc' , 'facebook' , 'instagram' , 'youtube' , 'image' , 'status' , 'sorting_product' ];
    protected $hidden = ['created_at','updated_at' , 'status'];
    protected $casts = [
        'sorting_product' => 'array',
    ];
}
