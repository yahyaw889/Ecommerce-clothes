<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','image','status'];
    protected $hidden = ['created_at','updated_at' , 'status'];
    
protected function image(): Attribute
    {
        
        return Attribute::make(
            get: function ($value) {
                return  asset('public/storage/' . ($value));
            }
        );
    }

}
