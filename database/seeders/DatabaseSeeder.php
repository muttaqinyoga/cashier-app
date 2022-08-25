<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123')
        ]);
        // Categori Seed
        $category = new Category;
        $idCategory = Uuid::uuid4()->getHex();
        $category->id = $idCategory;
        $category->name = 'Aneka Nasi';
        $category->slug = \Str::slug('Aneka Nasi', '-');
        $category->image = 'categories/category-foods.png';
        $category->save();
        // Food Seed
        $foodJson = '[
                        {
                            "name": "Nasi Goreng Biasa",
                            "price": 20000.00,
                            "stock": "Tersedia"
                        },
                        {
                            "name": "Nasi Goreng Spesial",
                            "price": 30000.00,
                            "stock": "Tersedia"
                        }
                    ]';
        $foods = json_decode($foodJson, true);
        foreach ($foods as $f) {
            $food = new Food;
            $food->id = Uuid::uuid4()->getHex();
            $food->name = $f['name'];
            $food->price = $f['price'];
            $food->image = 'foods/food-placeholder.jpeg';
            $food->status_stock = $f['stock'];
            $food->save();
            $food->categories()->attach($idCategory);
        }

        // 
    }
}
