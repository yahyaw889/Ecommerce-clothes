<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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




    protected static function booted()
    {
        self::deleted(function (Special $special) {
            $images = $special->image;

            if (is_array($images)) {
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            } elseif (is_string($images)) {
                Storage::disk('public')->delete($images);
            }

        });


        self::updated(function (Special $special) {
            $originalImages = $special->getOriginal('image');

            $currentImages = $special->image;

            if ($originalImages !== $currentImages) {
                $originalImagesArray = is_array($originalImages) ? $originalImages : json_decode($originalImages, true);
                $currentImagesArray = is_array($currentImages) ? $currentImages : json_decode($currentImages, true);

                if (is_array($originalImagesArray)) {
                    foreach ($originalImagesArray as $image) {
                        if (!in_array($image, $currentImagesArray, true)) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                } elseif (is_string($originalImages)) {
                    if ($originalImages !== $currentImages) {
                        Storage::disk('public')->delete($originalImages);
                    }
                }
            }
        });


        static::saving(function ($model) {
            $special = $model->special;
            if (empty(array_filter($special, fn ($item) => !empty($item['name']) || !empty($item['price']) || !empty($item['image']) || !empty($item['size']) || !empty($item['color']) || !empty($item['order_id']) ))) {
                $model->special = [];
            }
        });
    }



}
