<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory ,Notifiable;

    protected $fillable = ['first_name','last_name','email','phone','country','address','status','is_special' , 'notes' , 'invoice_number' , 'city_id'];

    public function items()
    {
        return $this->hasMany(OrderItems::class);

    }

    public function special()
    {
        return $this->hasMany(Special::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    


}
