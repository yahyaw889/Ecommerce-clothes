<?php

use App\Models\LoginUser;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LoginUser::class , 'user_id')->constrained('login_users')->cascadeOnDelete();
            $table->foreignIdFor(Order::class , 'order_id')->constrained('orders')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_users');
    }
};
