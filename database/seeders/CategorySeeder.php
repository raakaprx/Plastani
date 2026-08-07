<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Kemasan',
                'description' => 'Produk kemasan bioplastik ramah lingkungan untuk makanan dan minuman'
            ],
            [
                'name' => 'Peralatan Makan',
                'description' => 'Sendok, garpu, pisau, dan alat makan biodegradable'
            ],
            [
                'name' => 'Tas & Fashion',
                'description' => 'Tas belanja, aksesoris fashion dari bioplastik'
            ],
            [
                'name' => 'Wadah Penyimpanan',
                'description' => 'Wadah makanan, botol, dan kontainer penyimpanan'
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }

        $this->command->info('✓ Categories seeded successfully!');
    }
}
