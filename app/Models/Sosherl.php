<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sosherl extends Model
{

    use HasFactory;
    protected $fillable =
    ['name','phone','email','sosherl','disc' , 'facebook' , 'instagram' , 'youtube' , 'image' , 'status' , 'sorting_product' ];
    protected $hidden = ['created_at','updated_at' , 'status'];
    protected $casts = [
        'sorting_product' => 'array',
    ];

    protected static function booted()

    {
        self::deleted(function ($social): void {
            if (!empty($social->image) && is_string($social->image)) {
                Storage::disk('public')->delete($social->image);
            }

        });

        self::updated(function ($social) {
            $originalImage = $social->getOriginal('image');

            if ($originalImage !== $social->image && !empty($originalImage)) {
                Storage::disk('public')->delete($originalImage);
            }

        });
    }
}
