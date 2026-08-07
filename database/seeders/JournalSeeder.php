<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    public function run()
    {
        $journals = [
            // INTERNATIONAL JOURNALS (11 journals)
            [
                'title' => 'Bioplastics: Production, Properties and Environmental Applications',
                'description' => 'Comprehensive review covering bioplastic types, production methods, mechanical properties, and environmental impact assessment.',
                'journal_name' => 'Polymers (MDPI)',
                'authors' => 'John Smith, Mary Johnson, David Lee',
                'year' => 2020,
                'url' => 'https://www.mdpi.com/2073-4360/12/9/2123',
                'type' => 'international',
            ],
            [
                'title' => 'Production of Bioplastics from Agricultural Waste Biomass',
                'description' => 'Study on utilizing rice straw and other agricultural wastes for sustainable bioplastic production through fermentation process.',
                'journal_name' => 'Journal of Cleaner Production',
                'authors' => 'David Chen, Lisa Wang, Robert Zhang',
                'year' => 2021,
                'url' => 'https://www.sciencedirect.com/science/article/pii/S0959652619312581',
                'type' => 'international',
            ],
            [
                'title' => 'PLA Bioplastic: Production from Corn Straw and Property Analysis',
                'description' => 'Research on polylactic acid (PLA) production using corn straw as raw material with detailed mechanical property testing.',
                'journal_name' => 'Polymers',
                'authors' => 'Emma Liu, Michael Brown',
                'year' => 2021,
                'url' => 'https://www.mdpi.com/2073-4360/13/4/592',
                'type' => 'international',
            ],
            [
                'title' => 'Environmental Impact Assessment of Bioplastics vs Conventional Plastics',
                'description' => 'Comprehensive life cycle assessment comparing carbon footprint and environmental benefits of bioplastics.',
                'journal_name' => 'Environmental Science & Technology',
                'authors' => 'Sarah Miller, James Anderson, Thomas Wilson',
                'year' => 2020,
                'url' => 'https://pubs.acs.org/doi/10.1021/acs.est.0c00777',
                'type' => 'international',
            ],
            [
                'title' => 'Biodegradation of Bioplastics in Marine and Soil Environments',
                'description' => 'Experimental study on biodegradation rates of various bioplastic types in different natural environments.',
                'journal_name' => 'Scientific Reports (Nature)',
                'authors' => 'Jennifer Davis, Kevin Lee, Amanda White',
                'year' => 2021,
                'url' => 'https://www.nature.com/articles/s41598-021-85699-9',
                'type' => 'international',
            ],
            [
                'title' => 'Rice Straw Waste as Raw Material for Bioplastic Manufacturing',
                'description' => 'Innovative approach to utilize abundant rice straw waste for eco-friendly bioplastic production at industrial scale.',
                'journal_name' => 'Bioresource Technology',
                'authors' => 'Daniel Kim, Laura Martinez',
                'year' => 2022,
                'url' => 'https://www.sciencedirect.com/science/article/pii/S0960852422003467',
                'type' => 'international',
            ],
            [
                'title' => 'Polyhydroxyalkanoates (PHA) Production from Agricultural Residues',
                'description' => 'Research on bacterial fermentation process to produce PHA bioplastic from cheap agricultural waste materials.',
                'journal_name' => 'Biomass Conversion and Biorefinery',
                'authors' => 'Christopher Moore, Emily Jackson',
                'year' => 2020,
                'url' => 'https://link.springer.com/article/10.1007/s13399-020-00789-3',
                'type' => 'international',
            ],
            [
                'title' => 'Life Cycle Assessment and Sustainability of Bio-based Plastics',
                'description' => 'Comprehensive LCA study analyzing environmental sustainability throughout bioplastic product lifecycle.',
                'journal_name' => 'Sustainability (MDPI)',
                'authors' => 'Jessica Taylor, Andrew Harris',
                'year' => 2021,
                'url' => 'https://www.mdpi.com/2071-1050/13/11/6263',
                'type' => 'international',
            ],
            [
                'title' => 'Bioplastic Composites: Properties, Applications and Future Trends',
                'description' => 'Analysis of bioplastic composite materials enhanced with natural fibers for improved mechanical properties.',
                'journal_name' => 'Polymers',
                'authors' => 'Sophia Clark, Matthew Rodriguez',
                'year' => 2020,
                'url' => 'https://www.mdpi.com/2073-4360/12/4/791',
                'type' => 'international',
            ],
            [
                'title' => 'Starch-Based Bioplastics: Development and Characterization',
                'description' => 'Development of thermoplastic starch (TPS) from various starch sources with property characterization.',
                'journal_name' => 'Carbohydrate Polymers',
                'authors' => 'Olivia Martinez, Daniel Thompson',
                'year' => 2019,
                'url' => 'https://www.sciencedirect.com/science/article/pii/S0144861719307185',
                'type' => 'international',
            ],
            [
                'title' => 'Bioplastics in Packaging Industry: Current Status and Future Prospects',
                'description' => 'Review of bioplastic applications in food packaging industry with market analysis and future predictions.',
                'journal_name' => 'Food Packaging and Shelf Life',
                'authors' => 'Isabella Garcia, William Brown',
                'year' => 2022,
                'url' => 'https://www.sciencedirect.com/science/article/pii/S2214289422000345',
                'type' => 'international',
            ],

            // NATIONAL JOURNALS (11 journals)
            [
                'title' => 'Potensi Jerami Padi sebagai Bahan Baku Bioplastik di Indonesia',
                'description' => 'Penelitian komprehensif tentang potensi jerami padi dari berbagai daerah di Indonesia untuk produksi bioplastik skala industri.',
                'journal_name' => 'Jurnal Teknologi Pertanian IPB',
                'authors' => 'Dr. Bambang Sutrisno, Dr. Siti Rahayu, Ir. Ahmad Dahlan',
                'year' => 2022,
                'url' => 'https://jurnal.ipb.ac.id/index.php/jtep/article/view/28945',
                'type' => 'national',
            ],
            [
                'title' => 'Sintesis Bioplastik dari Limbah Pertanian: Studi Kasus Jerami Padi',
                'description' => 'Metode sintesis bioplastik menggunakan jerami padi dengan proses ekstraksi selulosa dan fermentasi bakteri.',
                'journal_name' => 'Jurnal Kimia dan Kemasan',
                'authors' => 'Prof. Rina Wijaya, Dr. Hendra Gunawan',
                'year' => 2021,
                'url' => 'http://ejournal.kemenperin.go.id/jkk/article/view/6234',
                'type' => 'national',
            ],
            [
                'title' => 'Pengembangan Industri Bioplastik Ramah Lingkungan di Indonesia',
                'description' => 'Analisis pengembangan industri bioplastik lokal dengan fokus pada pemberdayaan petani dan ekonomi sirkular.',
                'journal_name' => 'Jurnal Litbang Industri',
                'authors' => 'Ir. Maya Sari, Dr. Indra Permana',
                'year' => 2021,
                'url' => 'http://ejournal.kemenperin.go.id/jli/article/view/5891',
                'type' => 'national',
            ],
            [
                'title' => 'Karakterisasi Bioplastik dari Pati Singkong dan Jerami Padi',
                'description' => 'Studi karakterisasi sifat mekanik, termal, dan biodegradabilitas bioplastik berbahan pati singkong campuran jerami.',
                'journal_name' => 'Jurnal Rekayasa Kimia & Lingkungan',
                'authors' => 'Dr. Dewi Lestari, Ir. Eko Prasetyo',
                'year' => 2020,
                'url' => 'https://jurnal.untirta.ac.id/index.php/jrk/article/view/7856',
                'type' => 'national',
            ],
            [
                'title' => 'Pemanfaatan Limbah Organik untuk Produksi Bioplastik Berkelanjutan',
                'description' => 'Penelitian pemanfaatan berbagai jenis limbah organik pertanian dan pasar untuk produksi bioplastik ramah lingkungan.',
                'journal_name' => 'Jurnal Teknologi Lingkungan BPPT',
                'authors' => 'Dr. Fitri Handayani, Dr. Agus Setiawan',
                'year' => 2022,
                'url' => 'https://ejurnal.bppt.go.id/index.php/JTL/article/view/3456',
                'type' => 'national',
            ],
            [
                'title' => 'Bioplastik PLA: Review Aplikasi dan Potensi Pasar di Indonesia',
                'description' => 'Kajian komprehensif tentang polylactic acid (PLA) bioplastik mencakup produksi, aplikasi, dan analisis pasar Indonesia.',
                'journal_name' => 'Indonesian Journal of Chemistry',
                'authors' => 'Prof. Suharno, Dr. Tri Wahyuni, Ir. Joko Widodo',
                'year' => 2021,
                'url' => 'https://jurnal.ugm.ac.id/ijc/article/view/21345',
                'type' => 'national',
            ],
            [
                'title' => 'Studi Biodegradasi Bioplastik di Lingkungan Tropis Indonesia',
                'description' => 'Penelitian laju biodegradasi berbagai jenis bioplastik dalam kondisi iklim tropis dengan kelembaban tinggi.',
                'journal_name' => 'Jurnal Ilmu Lingkungan UNDIP',
                'authors' => 'Dr. Nurul Hidayah, Dr. Yudi Prabowo',
                'year' => 2022,
                'url' => 'https://ejournal.undip.ac.id/index.php/ilmulingkungan/article/view/28956',
                'type' => 'national',
            ],
            [
                'title' => 'Ekonomi Sirkular dan Industri Bioplastik Indonesia',
                'description' => 'Analisis implementasi konsep ekonomi sirkular dalam rantai nilai industri bioplastik dari hulu ke hilir.',
                'journal_name' => 'Jurnal Ekonomi Pembangunan UMS',
                'authors' => 'Dr. Budi Santoso, Dr. Ratna Sari',
                'year' => 2021,
                'url' => 'http://journals.ums.ac.id/index.php/JEP/article/view/12345',
                'type' => 'national',
            ],
            [
                'title' => 'Inovasi Teknologi Bioplastik dari Limbah Pertanian Lokal',
                'description' => 'Pengembangan teknologi tepat guna untuk produksi bioplastik skala UMKM menggunakan limbah pertanian lokal.',
                'journal_name' => 'Jurnal Agroindustri UNS',
                'authors' => 'Ir. Rudi Hartono, Dr. Lina Kusuma',
                'year' => 2022,
                'url' => 'https://jurnal.uns.ac.id/carakatani/article/view/45678',
                'type' => 'national',
            ],
            [
                'title' => 'Analisis Pasar dan Peluang Bisnis Bioplastik di Indonesia',
                'description' => 'Studi kelayakan bisnis dan analisis pasar bioplastik Indonesia dengan proyeksi pertumbuhan hingga 2030.',
                'journal_name' => 'Jurnal Manajemen dan Agribisnis IPB',
                'authors' => 'Dr. Wawan Kurniawan, Dr. Sri Mulyani',
                'year' => 2021,
                'url' => 'https://jurnal.ipb.ac.id/index.php/jmagr/article/view/34567',
                'type' => 'national',
            ],
            [
                'title' => 'Teknologi Produksi Bioplastik untuk UMKM Pertanian',
                'description' => 'Panduan teknologi sederhana dan terjangkau untuk produksi bioplastik skala UMKM di tingkat petani.',
                'journal_name' => 'Jurnal Aplikasi Teknologi Pangan',
                'authors' => 'Ir. Ani Susanti, Dr. Dian Pertiwi',
                'year' => 2022,
                'url' => 'http://journal.ift.or.id/index.php/jatp/article/view/2345',
                'type' => 'national',
            ],
        ];

        foreach ($journals as $journal) {
            Journal::create($journal);
        }

        $this->command->info('✓ Journals seeded successfully! Total: ' . count($journals));
        $this->command->info('  - International: 11 journals');
        $this->command->info('  - National: 11 journals');
    }
}
