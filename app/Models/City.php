<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'price' , 'status' ];
    protected $hidden = ['status' , 'created_at' , 'updated_at'];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
