<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingSetting extends Model
{
    //
    protected $fillable = ['model_price', 'additional_pricing', 'tax'];
    protected $hidden = ['created_at', 'updated_at'];
    
}
