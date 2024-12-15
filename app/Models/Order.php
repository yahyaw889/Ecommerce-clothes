<?php

namespace App\Models;

use App\Notifications\OrderCreatedNotification;
use App\Observers\OrderObserve;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Notifications\Notification;

class Order extends Model
{
    use HasFactory ,Notifiable;

    protected $fillable = ['first_name','last_name','email','phone','country','address','status','is_special' , 'notes' , 'invoice_number'];

    public function items()
    {
        return $this->hasMany(OrderItems::class);

    }

    public function special()
    {
        return $this->hasMany(Special::class);
    }



}
