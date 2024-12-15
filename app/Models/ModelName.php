<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelName extends Model
{

    protected $fillable = ['name' , 'status'];
    protected $hidden = ['created_at','updated_at' , 'status'];

    public function model()
    {
        return $this->hasMany(Models::class , 'model_name_id');
    }
}
