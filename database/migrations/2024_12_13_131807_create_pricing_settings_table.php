<?php

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
        Schema::create('pricing_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('model_price' , 8 , 2);
            $table->decimal('additional_pricing' , 8 , 2);
            $table->decimal('tax' , 8 ,2);
            $table->timestamps();
        });

        App\Models\PricingSetting::create([
            'model_price' => 200,
            'additional_pricing' => 50,
            'tax' => 50,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_settings');
    }
};
