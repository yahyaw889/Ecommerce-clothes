<?php

use App\Models\LoginUser;
use App\Models\Product;
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
        Schema::create('product_love', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class , 'product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignIdFor(LoginUser::class , 'user_id')->constrained('login_users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_love');
    }
};
