<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'title' => 'Mengapa Bioplastik Adalah Solusi Masa Depan?',
                'excerpt' => 'Bioplastik menawarkan alternatif ramah lingkungan untuk mengurangi polusi plastik konvensional yang mengancam ekosistem.',
                'content' => '<h2>Krisis Plastik Global</h2><p>Dunia menghadapi krisis plastik yang semakin parah. Setiap tahunnya, jutaan ton plastik berakhir di lautan dan tempat pembuangan sampah...</p><h2>Bioplastik Sebagai Solusi</h2><p>Bioplastik dibuat dari bahan terbarukan seperti jerami, singkong, dan jagung. Material ini dapat terurai secara alami...</p>',
                'author' => 'Tim PLASTANI',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Jerami Padi: Dari Limbah Menjadi Emas Hijau',
                'excerpt' => 'Potensi jerami padi Indonesia yang melimpah dapat diubah menjadi bioplastik bernilai ekonomi tinggi.',
                'content' => '<h2>Potensi Jerami di Indonesia</h2><p>Indonesia menghasilkan jutaan ton jerami setiap tahun dari sawah padi...</p><h2>Proses Transformasi</h2><p>Jerami dapat diolah menjadi bioplastik melalui ekstraksi selulosa...</p>',
                'author' => 'Dr. Bambang Sutrisno',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(12),
            ],
            [
                'title' => 'Cara Memilih Produk Bioplastik yang Berkualitas',
                'excerpt' => 'Panduan lengkap memilih produk bioplastik yang benar-benar ramah lingkungan dan berkualitas.',
                'content' => '<h2>Kriteria Bioplastik Berkualitas</h2><p>Tidak semua produk berlabel "bio" adalah bioplastik sejati...</p><h2>Sertifikasi yang Perlu Dicari</h2><p>Produk bioplastik berkualitas memiliki sertifikasi internasional...</p>',
                'author' => 'Tim PLASTANI',
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(20),
            ],
        ];

        foreach ($articles as $article) {
            $article['slug'] = Str::slug($article['title']);
            Article::create($article);
        }

        $this->command->info('✓ Articles seeded successfully! Total: ' . count($articles));
    }
}
