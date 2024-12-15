<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\LoginUser;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
 use App\Models\Sosherl;
use App\Models\Special;
use App\Models\SubCategory;
use App\Models\User;
use Database\Factories\SpecialFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
 use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'yahya' ,
            'email'=> 'yahyaw889@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        User::create([
            'name' => 'ebrahim' ,
            'email'=> 'ebrahim@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        User::create([
            'name' => 'test' ,
            'email'=> 'test@gmail.com',
            'password' => Hash::make('123'),
        ]);

            // LoginUser::factory(50)->create();
            //  Category::factory(10)->create();
            // Brand::factory(10)->create();
            // Product::factory(100)->create();
            // Order::factory(100)->create();
            // OrderItems::factory(500)->create();


            // Sosherl::factory(1)->create();
            // Special::factory(5)->create();
    }
}
