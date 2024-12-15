<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Special extends Model
{
    use HasFactory;

    protected $fillable =
    [ 'name','order_id','price','discount','quantity','image','size' , 'color','status'];
    protected $hidden = ['created_at', 'updated_at' , 'status'];
    protected $casts = [
        'image' => 'array',
        'size' => 'array',
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    protected function name(): Attribute
    {
    return Attribute::make(
        get: fn (string $value) => $value . ' ' . 'Front' . (is_array($this->image) && count($this->image) === 2 ? ' And Back' : ''),
    );
    }

}
