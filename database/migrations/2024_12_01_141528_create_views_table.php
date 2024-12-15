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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->integer('views')->default(0);
            $table->timestamps();
        });


        \App\Models\Views::create([
            'views' => 0
        ]);
    }
    // i need to run seeder for this table here



        // Seed the views table with some initial data



    // i need to run seeder for this table here
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
