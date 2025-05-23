<?php

use App\Models\Category;
use App\Models\LoginUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete() ;
            $table->foreignId('Categore_id')->constrained('categories')->cascadeOnUpdate();
            $table->string('name');
            $table->text('description');
            $table->json('color');
            $table->string('washing_method');
            $table->json('size');
            $table->string('slug');
            $table->integer('quantity')->default(1);
            $table->integer('discount')->default(0);
            $table->integer('views')->default(0);
            $table->integer('unit_sold')->default(0);
            $table->decimal('price',10,2);
            $table->json('images')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
