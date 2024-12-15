<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderUser extends Model
{
    /** @use HasFactory<\Database\Factories\OrderUserFactory> */
    use HasFactory;
    protected $fillable = ['order_id','user_id'];

    protected $hidden = ['created_at','updated_at'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
