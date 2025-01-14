<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Models extends Model
{
    /** @use HasFactory<\Database\Factories\ModelsFactory> */
    use HasFactory;
    protected $fillable = ['image_forward','image_back','color','status' , 'model_name_id'];

    protected $hidden = ['created_at','updated_at' , 'status'];
    protected $casts = [
        'image_forward' => 'array',
        'image_back' => 'array',
    ];


    public function model()
    {
    return $this->belongsTo(ModelName::class , 'model_name_id'); // Incorrect
    }

// delete old images from filesystem 
    protected static function booted()

    {
        self::deleted(function ($model): void {
            if (!empty($model->image_forward) && is_string($model->image_forward)) {
                Storage::disk('public')->delete($model->image_forward);
            }

            if (!empty($model->image_back) && is_string($model->image_back)) {
                Storage::disk('public')->delete($model->image_back);
            }
        });

        self::updated(function ($model) {
            $originalImageForward = $model->getOriginal('image_forward');
            $originalImageBack = $model->getOriginal('image_back');

            if ($originalImageForward !== $model->image_forward && !empty($originalImageForward)) {
                Storage::disk('public')->delete($originalImageForward);
            }

            if ($originalImageBack !== $model->image_back && !empty($originalImageBack)) {
                Storage::disk('public')->delete($originalImageBack);
            }
        });
    }
}
