<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Kemasan Category (ID: 1)
            [
                'category_id' => 1,
                'name' => 'Kantong Belanja Bioplastik Premium',
                'description' => 'Kantong belanja ramah lingkungan dari bioplastik berbahan jerami padi. Kuat menahan beban hingga 5kg, dapat digunakan berulang kali, dan terurai secara alami dalam 6-12 bulan. Tersedia dalam berbagai ukuran.',
                'price' => 15000,
                'stock' => 100,
                'material_source' => 'Jerami Padi',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Kemasan Makanan Food-Grade',
                'description' => 'Kemasan makanan aman food-grade dari bioplastik PLA. Tahan panas hingga 60°C, aman untuk makanan berminyak, tidak mengandung BPA. Cocok untuk usaha kuliner dan katering.',
                'price' => 25000,
                'stock' => 75,
                'material_source' => 'PLA dari Singkong',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Box Kemasan Premium',
                'description' => 'Box kemasan eksklusif untuk produk premium. Desain elegan, dapat custom printing logo. Biodegradable dan cocok untuk gift packaging produk organik.',
                'price' => 35000,
                'stock' => 50,
                'material_source' => 'PBAT Campuran',
                'eco_rating' => 4,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],

            // Peralatan Makan Category (ID: 2)
            [
                'category_id' => 2,
                'name' => 'Set Sendok Garpu Bioplastik (12 pcs)',
                'description' => 'Set peralatan makan sekali pakai dari bioplastik. Satu set berisi 6 sendok + 6 garpu. Biodegradable, tidak mengandung BPA, aman untuk makanan panas. Ideal untuk acara outdoor dan catering.',
                'price' => 18000,
                'stock' => 200,
                'material_source' => 'PHA Organik',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Sedotan Ramah Lingkungan (50 pcs)',
                'description' => 'Sedotan bioplastik pengganti sedotan plastik konvensional. Tidak mudah lembek, tahan hingga 2 jam dalam minuman dingin. Tersedia warna natural. Pack berisi 50 pcs.',
                'price' => 12000,
                'stock' => 150,
                'material_source' => 'Jerami Padi',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Piring Biodegradable (10 pcs)',
                'description' => 'Piring sekali pakai dari bioplastik. Diameter 23cm, tahan makanan berminyak, tidak bocor. Cocok untuk pesta, piknik, dan acara outdoor. Terurai alami dalam 6 bulan.',
                'price' => 22000,
                'stock' => 80,
                'material_source' => 'PLA Food-Grade',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],

            // Tas & Fashion Category (ID: 3)
            [
                'category_id' => 3,
                'name' => 'Tas Belanja Premium Eco-Friendly',
                'description' => 'Tas belanja stylish dan kokoh dari bioplastik berkualitas tinggi. Desain modern dengan handle kuat, dapat menahan beban 8kg. Dapat digunakan ratusan kali sebelum terurai. Tersedia 3 warna: hijau, coklat, putih.',
                'price' => 45000,
                'stock' => 60,
                'material_source' => 'PBAT Premium',
                'eco_rating' => 4,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Tote Bag Bioplastik Custom',
                'description' => 'Tote bag bioplastik dengan opsi custom printing. Cocok untuk souvenir event, promosi brand eco-friendly. Minimum order 50 pcs untuk custom design.',
                'price' => 38000,
                'stock' => 100,
                'material_source' => 'PLA Printed',
                'eco_rating' => 4,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],

            // Wadah Penyimpanan Category (ID: 4)
            [
                'category_id' => 4,
                'name' => 'Wadah Makanan Bento Bioplastik',
                'description' => 'Wadah makanan bento dari bioplastik dengan 3 sekat. Aman untuk microwave (tanpa tutup), freezer-safe, BPA-free. Ukuran: 20x15x6cm. Tutup kedap udara untuk menjaga kesegaran makanan.',
                'price' => 42000,
                'stock' => 85,
                'material_source' => 'PLA Food-Grade',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Botol Minum Bioplastik 750ml',
                'description' => 'Botol minum reusable dari bioplastik PHA. Kapasitas 750ml, tidak mudah pecah, BPA-free. Dilengkapi tutup anti-tumpah dan tali pembawa. Cocok untuk olahraga dan traveling.',
                'price' => 55000,
                'stock' => 70,
                'material_source' => 'PHA Durable',
                'eco_rating' => 5,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'name' => 'Set Kontainer Penyimpanan (3 pcs)',
                'description' => 'Set 3 kontainer penyimpanan berbagai ukuran (500ml, 750ml, 1L). Transparan, kedap udara, stackable untuk hemat tempat. Aman untuk kulkas dan microwave.',
                'price' => 65000,
                'stock' => 55,
                'material_source' => 'PBAT Transparent',
                'eco_rating' => 4,
                'whatsapp_number' => '6285156000636',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $product['slug'] = Str::slug($product['name']);
            Product::create($product);
        }

        $this->command->info('✓ Products seeded successfully! Total: ' . count($products));
    }
}
