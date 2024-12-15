<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Views extends Model
{
    /** @use HasFactory<\Database\Factories\ViewsFactory> */
    use HasFactory;

    protected $fillable = ['views'];
}
