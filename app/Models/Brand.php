<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

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


    protected static function booted()

    {
        self::deleted(function ($brand): void {
            if (!empty($brand->image) && is_string($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }

        });

        self::updated(function ($brand) {
            $originalImage = $brand->getOriginal('image');

            if ($originalImage !== $brand->image && !empty($originalImage)) {
                Storage::disk('public')->delete($originalImage);
            }

        });
    }

}
