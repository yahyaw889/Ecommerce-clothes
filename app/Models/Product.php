<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'washing_method', 'brand_id', 'Categore_id',
        'description', 'color', 'size', 'slug', 'quantity',
        'discount', 'price', 'images', 'status', 'unit_sold'
    ];

    protected $hidden = ['created_at', 'updated_at','status'];
    protected $casts = [
        'images' => 'array',
        'size' => 'array',
        'color' => 'array'
    ];



    public function getFinalPriceAttribute()
    {
        return round($this->price -  ($this->discount ), 2);
    }

    public function getTotalPriceAttribute()
    {
        return '$ '.round(  $this->final_price * 1, 2);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categore()
    {
        return $this->belongsTo(Category::class, 'Categore_id');
    }


  

// using many scope to handle different types of querys

    public function scopeFilter($query, $filters)
{
    return $query
        ->when(isset($filters['priceFrom']), function ($query) use ($filters) {
            $query->where('price', '>=', $filters['priceFrom']);
        })
        ->when(isset($filters['priceTo']), function ($query) use ($filters) {
            $query->where('price', '<=', $filters['priceTo']);
        })
        ->when(isset($filters['name']), function ($query) use ($filters) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        })
        ->when(isset($filters['category']), function ($query) use ($filters) {
            $query->whereIn('Categore_id', $filters['category']);
        })
        ->when(isset($filters['brand']), function ($query) use ($filters) {
            $query->whereIn('brand_id', $filters['brand']);
        });
}


    public function scopeActive($query)
    {
        return $query->where('status', 1)
            ->whereHas('categore', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('brand', function ($query) {
                $query->where('status', 1);
            })
            ->quantity();
    }

    public function scopeSorting($query, $sorting)
    {
        $allowedSorting = [
            'new' => 'created_at',
            'sold' => 'unit_sold',
            'views' => 'views',
            'discount'=> 'discount',
        ];

        if (is_array($sorting)) {
            foreach ($sorting as $key) {
                if (array_key_exists($key, $allowedSorting)) {
                    $query->latest($allowedSorting[$key]);
                }
            }
        } else {
            $query->latest('created_at');
        }
    }

    public function scopeQuantity($query)
    {
        $quantity = Sosherl::query()->first()->quantity;

        if ($quantity) {
            $query->where('quantity', '>', 0);
        }

        return $query;
    }


}
