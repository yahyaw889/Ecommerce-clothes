<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLove extends Model
{
    /** @use HasFactory<\Database\Factories\ProductLoveFactory> */
    use HasFactory;
    protected $fillable = ['user_id', 'product_id'];
    protected $hidden = ['created_at', 'updated_at' , 'user_id' , 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
