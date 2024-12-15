<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
