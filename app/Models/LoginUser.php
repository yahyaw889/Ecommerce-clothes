<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
 
class LoginUser extends Model
{
    use HasFactory , HasApiTokens;
    protected $fillable =['first_name','last_name','phone','email','country','password','status'];
    protected $hidden = ['password' , 'created_at' , 'updated_at' , 'status' , 'remember_token'];




    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
