<?php

use App\Models\Sosherl;
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
        Schema::create('sosherls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('disc');
            $table->string('social')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('image')->nullable();
            $table->boolean('quantity')->default(1);
            $table->json('sorting_product')->nullable();
            $table->timestamps();
        });

        Sosherl::create([
            'name' => 'Artive',
            'phone' => '+01555208734',
            'email' => 'artive@example.com',
            'disc' => 'Discover the latest trends in art and design.',
            'social' => 'Social Artive',
            'quantity' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sosherls');
    }
};
