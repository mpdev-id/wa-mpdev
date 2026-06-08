<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use App\Models\Post;
use App\Models\Postmeta;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Postcategory;

class IndonesianContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Clean existing records for 'id' locale so it can be re-run
        Option::where('lang', 'id')->delete();
        Post::where('lang', 'id')->delete();
        Menu::where('lang', 'id')->delete();
        Category::where('lang', 'id')->delete();

        // 2. Add 'id' into 'languages' option for 'en'
        $languagesOption = Option::where('key', 'languages')->first();
        if ($languagesOption) {
            $langs = json_decode($languagesOption->value, true);
            if (!isset($langs['id'])) {
                $langs['id'] = 'Indonesian';
                $languagesOption->value = json_encode($langs);
                $languagesOption->save();
            }
        } else {
            Option::create([
                'key' => 'languages',
                'value' => json_encode(['en' => 'English', 'id' => 'Indonesian']),
                'lang' => 'en'
            ]);
        }

        // Also create a 'languages' option for 'id' locale
        Option::create([
            'key' => 'languages',
            'value' => json_encode(['en' => 'English', 'id' => 'Indonesian']),
            'lang' => 'id'
        ]);

        // 3. Populate Options for 'id'
        $homePageData = [
            "heading" => [
                "title" => "Kirim Pesan Massal & Otomatisasi WhatsApp Bisnis dengan <strong>WHASAPI</strong>",
                "left_button_link" => "/pricing",
                "right_button_link" => "/pricing"
            ],
            "brand" => [
                "title" => "Lebih dari 32 Ribu+ bisnis berkembang bersama WHASAPI",
                "status" => "active"
            ],
            "cta" => [
                "title" => "Lebih dari 13.000 tim menggunakan platform WHASAPI"
            ],
            "features" => [
                "title" => "Jelajahi Fitur Pemasaran WhatsApp Unggulan Kami 🚀",
                "status" => "active"
            ],
            "platform" => [
                "title" => "Bangun Chatbot WhatsApp Anda Sendiri Tanpa Coding",
                "description" => "Kami mempermudah Anda untuk memantau, merancang, dan meluncurkan chatbot interaktif. Buat, uji, dan kembangkan bot Anda secara visual menggunakan antarmuka online tanpa kode.",
                "button_title" => "Mulai Sekarang",
                "button_link" => "/pricing"
            ],
            "account_area" => [
                "heading" => "Daftar Akun Gratis Anda",
                "subheading" => "Hanya butuh waktu 3 menit untuk memulai",
                "description" => "Hubungkan nomor WhatsApp Anda, buat auto-responder, dan mulailah mengirimkan pesan otomatis hari ini.",
                "form_link" => "/register/2",
                "status" => "active",
                "button_link1" => "/pricing",
                "button_link2" => "/pricing"
            ],
            "about" => [
                "title" => "Otomatisasi WhatsApp 24/7",
                "description" => "Bot WhatsApp bekerja tanpa henti untuk merespons pertanyaan pelanggan secara instan, menyaring prospek, dan mengirim notifikasi otomatis.",
                "action_area_title" => "Mari buat chatbot Whatsapp 🚀"
            ],
            "top_faq" => [
                "title" => "WHASAPI untuk Kebutuhan Bisnis Modern 😇",
                "description" => "WHASAPI adalah solusi terbaik untuk pertumbuhan bisnis Anda, dengan fitur-fitur canggih yang mudah digunakan."
            ],
            "integration" => [
                "title" => "Integrasi Terbaik 🤝🏻"
            ],
            "testimonial" => [
                "title" => "Testimoni Pengguna 🏅"
            ],
            "calltoaction" => [
                "title" => "Temukan cara yang lebih baik untuk <br> mengelola pesan & kampanye",
                "button_title" => "Mulai Sekarang",
                "button_link" => "/pricing"
            ],
             "right_button_image" => "/uploads/23/03/16782229849w7zEZUReAELpYZzBhfy.png",
             "left_button_image" => "/uploads/23/03/16782229844xnXg3VVbopZTUmCVkoP.png",
             "hero_left_image" => "/uploads/23/04/1680991267dcZhpgfhm46WulMpbFmU.png",
             "hero_image" => "/uploads/wasapi/hero_image.png",
             "testimonial_cover" => "/uploads/23/04/1680990550b4VGZjg5I0mHrS0ZEzEO.png",
             "integration_cover" => "/uploads/23/04/1680691072MdDwD8iZV7nFu32OLg7Z.png",
             "integration_left" => "/uploads/23/04/1680687785yK5jB6wU8F0njvElGAqE.png",
             "integration_right" => "/uploads/23/04/1680687785b4Qk1bP8HMnMCuedEqOo.png",
             "hero_right_image" => "/uploads/23/04/1680991267IpQIAwGSbY5ZIFRwO6Pp.png",
             "cta_logo" => "/uploads/wasapi/logo.png",
             "cta_thumbnail" => "/uploads/wasapi/hero_image.png",
             "platform_thumbnail" => "/uploads/wasapi/hero_image.png",
             "account_area_thumbnail" => "/uploads/wasapi/hero_image.png",
             "account_area_top_thumbnail" => "/uploads/23/03/1678039536eRShFlGn3Uw9tN1X14E0.png",
             "account_area_bottom_thumbnail" => "/uploads/23/03/16780395365JXHyeVBXUVAldB1V5Se.png",
             "account_footer_left_image" => "/uploads/23/03/16780395365VbXNjp8vpREwrrQ8R44.png",
             "account_footer_right_image" => "/uploads/23/03/1678039536cX0XI21coIdJ3M1Im54A.png",
             "faq_cover" => "/uploads/23/04/1680990550DfwMWKaP9MfCYR6fPued.png",
             "about_cover" => "/uploads/23/04/1680989187xEbFX8vGfdBmWfMkb3Xo.gif"
         ];

        $headerFooterData = [
            "header" => [
                "announcement_type" => "PROMO TERBATAS:",
                "announcement_title" => "Dapatkan diskon paket tahunan pertama Anda sekarang juga!",
                "announcement_link" => "/contact"
            ],
            "footer" => [
                "title" => "Siap untuk Memulai Otomatisasi? 🚀",
                "description" => "Gunakan WHASAPI sekarang untuk mengotomatisasikan percakapan dan meningkatkan konversi penjualan Anda.",
                "right_image_link" => "/pricing",
                "left_image_link" => "/pricing"
            ],
            "footer_button_image" => "/uploads/23/03/1678211121jY056A0fKjEFLQN7lJeZ.png",
            "footer_left_button_image" => "/uploads/23/03/1678211121yYLog9HsVoV5QCJAvX4g.png"
        ];

        $whyChooseData = [
            "title" => "Mengapa Memilih WHASAPI? 🏅",
            "subtitle" => "Keunggulan",
            "left_button_link" => "/pricing",
            "right_button_link" => "/pricing",
            "left_block_title" => "Pengguna Aktif",
            "left_block_value" => "1200",
            "center_block_title" => "Total Pelanggan",
            "center_block_value" => "1500",
            "right_block_title" => "Ulasan Positif",
            "right_block_value" => "600",
            "left_button_image" => "/uploads/23/03/1678120554l1bhGUjz28tmiBtCqTK6.png",
            "right_button_image" => "/uploads/23/03/1678120554IAVDm9xBLZXYtGAamM0I.png",
            "left_block_image" => "/uploads/23/03/1678120554hneDbhf9WG6aXvdV0h6q.png",
            "center_block_image" => "/uploads/23/03/1678120554PcP79pqqlziQ71Yf1T70.png",
            "right_block_image" => "/uploads/23/03/1678120554dGH99dtfF4slpDVVHjT1.png"
        ];

        $aboutData = [
            "about_image_1" => "/uploads/23/03/1678140418eqwc9ocl8MfpeP0fEznT.png",
            "about_image_2" => "/uploads/23/03/16781404191Btcr8D8FJTBo3SiTcQA.png",
            "breadcrumb_title" => "Tentang WHASAPI",
            "section_title" => "Berpengalaman Menyediakan API Gateway & Otomatisasi WhatsApp Terbaik",
            "experience" => "5",
            "experience_title" => "Tahun Inovasi",
            "description" => "Kami percaya komunikasi cepat adalah kunci sukses bisnis. WHASAPI hadir untuk mempermudah akses ke WhatsApp Business API, memberikan infrastruktur tangguh untuk mengirim pesan massal, chatbot interaktif, dan notifikasi otomatis. <br> Dengan dedikasi tinggi, kami memastikan layanan kami selalu stabil dengan uptime maksimal demi kelancaran bisnis Anda.",
            "button_title" => "Lihat Fitur Utama",
            "button_link" => "/features",
            "facilities" => "CS Cepat Tanggap, API Gateway Stabil, Harga Hemat & Bersahabat, Dashboard Mudah Dipahami, Fitur Selalu Diperbarui",
            "introducing_video" => "https://www.youtube.com/watch?v=Fu3MIwF-LJw"
        ];

        $counterData = [
            "experience" => "5",
            "active_customers" => "900",
            "positive_reviews" => "200",
            "satisfied_customers" => "800"
        ];

        $options = [
            'home-page' => $homePageData,
            'header_footer' => $headerFooterData,
            'why-choose' => $whyChooseData,
            'about' => $aboutData,
            'counter' => $counterData,
            'seo_home' => [
                "site_name" => "WHASAPI - Aplikasi Otomatisasi & API Gateway WhatsApp Terbaik",
                "matatag" => "whatsapp gateway, whatsapp api business, bulk whatsapp, kirim whatsapp massal, chatbot whatsapp, api wa, whasapi, otomatisasi whatsapp",
                "matadescription" => "WHASAPI adalah platform WhatsApp Business API & Gateway terbaik untuk mengirim pesan massal, otomatisasi chatbot, notifikasi otomatis, dan integrasi CRM. Tingkatkan konversi penjualan Anda hari ini!",
                "twitter_site_title" => "whasapi",
                "preview" => ""
            ],
            'seo_blog' => [
                "site_name" => "Blog & Kabar Terbaru Pemasaran WhatsApp - WHASAPI",
                "matatag" => "blog whatsapp, tips whatsapp marketing, tutorial api wa, panduan chatbot, info whasapi",
                "matadescription" => "Temukan artikel menarik, panduan pemasaran, tutorial, dan tips optimasi bisnis menggunakan WhatsApp Business API bersama WHASAPI.",
                "preview" => ""
            ],
            'seo_about' => [
                "site_name" => "Tentang WHASAPI - Penyedia WhatsApp API Gateway Terpercaya",
                "matatag" => "tentang whasapi, profil whasapi, visi misi whasapi, whatsapp gateway indonesia",
                "matadescription" => "Ketahui lebih lanjut tentang visi, misi, dan tim di balik WHASAPI. Kami berdedikasi menyediakan solusi komunikasi WhatsApp yang andal dan aman untuk perkembangan bisnis Anda.",
                "preview" => ""
            ],
            'seo_pricing' => [
                "site_name" => "Paket Harga Layanan WhatsApp API Gateway Murah - WHASAPI",
                "matatag" => "harga whatsapp api, paket whatsapp gateway, biaya whatsapp business api, daftar harga whasapi",
                "matadescription" => "Pilih paket harga WHASAPI yang paling sesuai dengan kebutuhan bisnis Anda. Mulai dari paket uji coba gratis hingga paket enterprise tanpa biaya tersembunyi.",
                "preview" => ""
            ],
            'seo_contact' => [
                "site_name" => "Hubungi Customer Support WHASAPI - Kontak Layanan WhatsApp",
                "matatag" => "kontak whasapi, hubungi kami, cs whasapi, support whatsapp gateway",
                "matadescription" => "Ada pertanyaan atau butuh bantuan integrasi? Hubungi tim support WHASAPI yang ramah melalui form kontak atau email. Kami siap membantu Anda 24/7.",
                "preview" => ""
            ],
            'seo_faq' => [
                "site_name" => "Tanya Jawab (FAQ) Penggunaan WHASAPI WhatsApp Gateway",
                "matatag" => "faq whasapi, tanya jawab whatsapp gateway, panduan whasapi, bantuan wa gateway",
                "matadescription" => "Temukan jawaban cepat atas pertanyaan yang sering diajukan mengenai cara mendaftar, integrasi API, chatbot, pembayaran, dan pengaturan di WHASAPI.",
                "preview" => ""
            ],
            'seo_team' => [
                "site_name" => "Kenali Tim Ahli di Balik Pengembangan WHASAPI",
                "matatag" => "tim whasapi, developer whasapi, profesional whatsapp api",
                "matadescription" => "Temui para profesional IT, desainer, dan analis produk berbakat yang terus berinovasi membangun platform otomatisasi WhatsApp terbaik di WHASAPI.",
                "preview" => ""
            ],
            'seo_features' => [
                "site_name" => "Fitur Lengkap WhatsApp API Gateway & Chatbot AI - WHASAPI",
                "matatag" => "fitur whatsapp api, chatbot wa otomatis, rest api whatsapp, wa blast, broadcast whatsapp",
                "matadescription" => "Jelajahi fitur andalan WHASAPI: pengiriman pesan massal (broadcast), tombol interaktif, penjadwalan pesan, auto responder pintar, dan REST API developers.",
                "preview" => ""
            ],
            'seo_how_its_works' => [
                "site_name" => "Cara Kerja Platform WhatsApp Gateway WHASAPI",
                "matatag" => "cara kerja whatsapp gateway, integrasi whatsapp api, cara pakai whasapi",
                "matadescription" => "Pelajari bagaimana cara menghubungkan nomor WhatsApp Anda, mengatur pesan otomatis, dan mengintegrasikan API WHASAPI ke aplikasi Anda dalam 3 langkah mudah.",
                "preview" => ""
            ],
            'theme_path' => 'frontend.index-1',
            'base_currency' => [
                "name" => "IDR",
                "icon" => "Rp",
                "position" => "left"
            ],
            'tax' => 0,
            'invoice_data' => [
                "company_name" => "WHASAPI",
                "address" => "San francisco",
                "city" => "California",
                "country" => "USA",
                "post_code" => "12345"
            ]
        ];

        foreach ($options as $key => $value) {
            Option::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'lang' => 'id'
            ]);
        }

        // 4. Seed Menu for 'id'
        Menu::create([
            'name' => 'Header',
            'position' => 'main-menu',
            'data' => '[{"text":"Beranda","icon":"","href":"\/","target":"_self","title":""},{"text":"Harga","icon":"empty","href":"\/pricing","target":"_self","title":""},{"text":"Tentang Kami","icon":"empty","href":"\/about","target":"_self","title":""},{"text":"Fitur","icon":"empty","href":"\/features","target":"_self","title":""},{"text":"Blog","icon":"empty","href":"\/blogs","target":"_self","title":""},{"text":"Kontak","icon":"empty","href":"\/contact","target":"_self","title":""}]',
            'lang' => 'id',
            'status' => 1
        ]);

        // 5. Seed Categories for 'id'
        $categories = [
            ['title' => 'Chatbot', 'slug' => 'chatbot', 'type' => 'blog_category', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'Pemasaran Sasaran', 'slug' => 'target-marketing', 'type' => 'blog_category', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'Pesan Tombol', 'slug' => 'button-message', 'type' => 'blog_category', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'Templat Pesan', 'slug' => 'template-message', 'type' => 'blog_category', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'Pesan Lokasi', 'slug' => 'location-message', 'type' => 'blog_category', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'dukungan', 'slug' => 'support', 'type' => 'tags', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'pemasaran', 'slug' => 'marketing', 'type' => 'tags', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'whatsapp', 'slug' => 'whatsapp', 'type' => 'tags', 'status' => 1, 'is_featured' => 1, 'lang' => 'id'],
            ['title' => 'chatbot', 'slug' => 'chatbot-tag', 'type' => 'tags', 'status' => 1, 'is_featured' => 1, 'lang' => 'id']
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $inserted = Category::create($cat);
            $categoryMap[$cat['type'] . '-' . $cat['slug']] = $inserted->id;
        }

        // 6. Seed Posts and Postmetas for 'id'
        $posts = [
            // FAQs
            [
                'title' => 'Mengapa bisnis Anda membutuhkan chatbot?',
                'slug' => 'why-does-your-business-need-a-chatbot',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Chatbot WHASAPI siap melayani pelanggan 24/7 secara otomatis. Hemat waktu dan biaya operasional dengan menjawab FAQ secara instan dan menyaring prospek panas secara otomatis tanpa intervensi manual.'
                ]
            ],
            [
                'title' => 'Apakah saya memerlukan kartu kredit untuk mendaftar?',
                'slug' => 'do-i-need-a-credit-card-to-sign-up',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Tidak, Anda dapat langsung mencoba uji coba gratis WHASAPI selama 10 hari hanya menggunakan alamat email tanpa perlu memasukkan informasi kartu kredit.'
                ]
            ],
            [
                'title' => 'Apakah Anda menawarkan jaminan uang kembali 30 hari?',
                'slug' => 'do-you-offer-a-30-day-money-back-guarantee',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Ya, kami berkomitmen memberikan layanan terbaik. Jika Anda merasa kurang puas dalam waktu 30 hari pertama, hubungi kami untuk pengembalian dana penuh.'
                ]
            ],
            [
                'title' => 'Layanan Keuangan',
                'slug' => 'financial-services',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Kirim informasi saldo secara instan, OTP transaksional, pengingat tagihan otomatis, dan jawab keluhan nasabah secara otomatis dengan keamanan terenkripsi.'
                ]
            ],
            [
                'title' => 'Bagaimana pengaruhnya terhadap bisnis Makanan & Restoran.',
                'slug' => 'how-it-will-take-impact-for-food-restaurants-business',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Terima pesanan otomatis langsung dari WhatsApp, kirim rekomendasi menu harian, konfirmasi pembayaran, dan bagikan status pengiriman makanan secara real-time.'
                ]
            ],
            [
                'title' => 'Sinkronisasi otomatis secara real time',
                'slug' => 'automatically-sync-in-real-time-faq',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Semua data dan pesan Anda disinkronkan secara otomatis dan real-time di seluruh perangkat Anda.'
                ]
            ],
            [
                'title' => 'Uji Coba 10 Hari Gratis',
                'slug' => 'free-10-day-trial-faq',
                'type' => 'faq',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Daftar sekarang dan nikmati akses penuh ke semua fitur premium WHASAPI selama 10 hari penuh secara gratis untuk merasakan manfaatnya langsung.'
                ]
            ],

            // Features
            [
                'title' => 'Pesan templat',
                'slug' => 'template-messaging',
                'type' => 'feature',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Desain pesan promosi atau notifikasi dengan templat dinamis untuk meningkatkan rasio keterbacaan pesan.',
                    'main_description' => 'Buat kampanye penyiaran yang menarik dengan menyisipkan nama pelanggan, detail pesanan, atau variabel khusus lainnya secara dinamis. Maksimalkan engagement pelanggan Anda dengan format pesan terstruktur.',
                    'preview' => '/uploads/23/03/1678143851hf9IuC24SAhOJ1dLId94.png',
                    'banner' => '/uploads/23/03/1678143446kru2cExnnwONsqF55Nc6.png'
                ]
            ],
            [
                'title' => 'Tombol tindakan',
                'slug' => 'actions-buttons',
                'type' => 'feature',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Kirim pesan dengan tombol interaksi cepat (quick reply) atau tombol ajakan bertindak (call-to-action) langsung di obrolan WhatsApp.',
                    'main_description' => 'Memudahkan pelanggan untuk merespons pesan Anda hanya dengan satu ketukan. Tingkatkan rasio konversi promo Anda dengan mengarahkan pelanggan langsung ke halaman website atau panggilan telepon instan.',
                    'preview' => '/uploads/23/03/1678144536donXXxCjluUjy3vZEjEO.png',
                    'banner' => '/uploads/23/03/1678177857DqLCX7BJooFwWYFnY3mH.png'
                ]
            ],
            [
                'title' => 'Penjawab Otomatis (BOT)',
                'slug' => 'auto-responder-bot',
                'type' => 'feature',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Balas pesan pelanggan secara instan dan otomatis menggunakan bot pintar berdasarkan kata kunci tertentu.',
                    'main_description' => 'Jangan biarkan pelanggan menunggu. Sistem auto responder WHASAPI akan otomatis mengenali kata kunci pertanyaan pelanggan dan memberikan jawaban yang tepat dalam hitungan milidetik.',
                    'preview' => '/uploads/23/03/1678145544w3aon4jCrAciwsSaTXCo.png',
                    'banner' => '/uploads/23/03/1678178152NI8xZNWqa96WzC13Aytg.gif'
                ]
            ],
            [
                'title' => 'Jadwalkan pesan',
                'slug' => 'schedule-message',
                'type' => 'feature',
                'status' => '1',
                'featured' => '0',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Rencanakan dan jadwalkan pengiriman pesan promosi atau pengingat penting secara otomatis pada waktu tertentu.',
                    'main_description' => 'Atur waktu pengiriman pesan terbaik sesuai dengan zona waktu pelanggan Anda. Sangat cocok untuk mengirimkan ucapan ulang tahun otomatis, pengingat jatuh tempo pembayaran, atau promosi musiman.',
                    'preview' => '/uploads/23/03/1678176166I16bhCDLteYzyEXmiydB.png'
                ]
            ],
            [
                'title' => 'Pesan massal',
                'slug' => 'bulk-message',
                'type' => 'feature',
                'status' => '1',
                'featured' => '0',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Kirim pesan ke ribuan kontak sekaligus dengan cepat dan aman tanpa khawatir diblokir.',
                    'main_description' => 'Siarkan pengumuman penting atau promosi produk terbaru ke seluruh database pelanggan Anda hanya dengan sekali klik. Sistem kami didesain untuk meminimalisir risiko blokir nomor.',
                    'preview' => '/uploads/23/03/1678176166I16bhCDLteYzyEXmiydB.png'
                ]
            ],
            [
                'title' => 'REST API untuk Aplikasi',
                'slug' => 'reset-api-for-app',
                'type' => 'feature',
                'status' => '1',
                'featured' => '0',
                'lang' => 'id',
                'metas' => [
                    'excerpt' => 'Integrasikan WhatsApp Gateway ke dalam aplikasi internal, sistem CRM, atau website e-commerce Anda dengan mudah.',
                    'main_description' => 'Gunakan REST API WHASAPI yang cepat dan aman untuk mengirimkan notifikasi transaksi otomatis, OTP login, pembaruan status pengiriman barang, dan lainnya secara real-time.',
                    'preview' => '/uploads/23/03/1678176166I16bhCDLteYzyEXmiydB.png'
                ]
            ],

            // Blogs
            [
                'title' => 'Tingkatkan Pertumbuhan Bisnis Lewat WhatsApp Pemasaran Kontekstual',
                'slug' => 'boost-your-business-growth-with-whatsapp-quality-based-messaging',
                'type' => 'blog',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'category_slugs' => ['blog_category-target-marketing', 'tags-whatsapp'],
                'metas' => [
                    'preview' => '/uploads/23/03/1678128734p2mgTWEhEKwm5MsxhunY.png',
                    'short_description' => 'Pelajari bagaimana pesan berbasis kualitas dan personalisasi dapat membantu merek Anda membangun kedekatan emosional dengan pelanggan.',
                    'main_description' => 'Pelajari bagaimana pesan berbasis kualitas dan personalisasi dapat membantu merek Anda membangun kedekatan emosional dengan pelanggan. Di era modern ini, penyiaran pesan yang dipersonalisasi dengan context yang tepat sangat menentukan tingkat konversi penjualan.',
                    'seo' => json_encode([
                        'title' => 'Tingkatkan Pertumbuhan Bisnis Lewat WhatsApp Pemasaran Kontekstual',
                        'description' => 'Pelajari bagaimana pesan berbasis kualitas dan personalisasi dapat membantu merek Anda membangun kedekatan emosional dengan pelanggan.',
                        'tags' => 'wa, whatsapp, whasapi, whatsserve'
                    ])
                ]
            ],
            [
                'title' => 'Iklan Klik-ke-WhatsApp (Click-to-Chat) Sebagai Strategi Utama Marketing',
                'slug' => 'click-to-chat-ads-on-whatsapp-a-vital-tool-for-your-marketing-arsenal',
                'type' => 'blog',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'category_slugs' => ['blog_category-target-marketing', 'blog_category-button-message', 'tags-marketing', 'tags-whatsapp'],
                'metas' => [
                    'preview' => '/uploads/23/03/1678129064eIpoGudjOtETN0JhQRzG.png',
                    'short_description' => 'Temukan cara mengarahkan calon pembeli dari iklan Facebook atau Instagram langsung masuk ke obrolan WhatsApp Anda.',
                    'main_description' => 'Temukan cara mengarahkan calon pembeli dari iklan Facebook atau Instagram langsung masuk ke obrolan WhatsApp Anda. Cara ini terbukti memangkas waktu pertimbangan pembeli dan melipatgandakan tingkat konversi Anda secara instan.',
                    'seo' => json_encode([
                        'title' => 'Iklan Klik-ke-WhatsApp (Click-to-Chat) Sebagai Strategi Utama Marketing',
                        'description' => 'Temukan cara mengarahkan calon pembeli dari iklan Facebook atau Instagram langsung masuk ke obrolan WhatsApp Anda.',
                        'tags' => 'wa, whatsapp, whasapi, whatsserve'
                    ])
                ]
            ],
            [
                'title' => 'Mengapa ChatGPT Tidak Dapat Menggantikan AI Percakapan Khusus Bisnis?',
                'slug' => 'why-chatgpt-cannot-replace-conversational-ai-platforms',
                'type' => 'blog',
                'status' => '1',
                'featured' => '1',
                'lang' => 'id',
                'category_slugs' => ['blog_category-chatbot', 'tags-whatsapp', 'tags-chatbot'],
                'metas' => [
                    'preview' => '/uploads/23/03/16781292529emrzSmYnHCskq8JUxIb.png',
                    'short_description' => 'Meskipun ChatGPT sangat pintar, bisnis memerlukan alur chatbot terstruktur untuk mengelola transaksi dan menjaga keamanan data.',
                    'main_description' => 'Meskipun ChatGPT sangat pintar, bisnis memerlukan alur chatbot terstruktur untuk mengelola transaksi dan menjaga keamanan data. WHASAPI menjembatani hal ini dengan menyediakan visual builder chatbot yang andal dan terstruktur.',
                    'seo' => json_encode([
                        'title' => 'Mengapa ChatGPT Tidak Dapat Menggantikan AI Percakapan Khusus Bisnis?',
                        'description' => 'Meskipun ChatGPT sangat pintar, bisnis memerlukan alur chatbot terstruktur untuk mengelola transaksi dan menjaga keamanan data.',
                        'tags' => 'wa, whatsapp, whasapi, whatsserve'
                    ])
                ]
            ]
        ];

        foreach ($posts as $postSpec) {
            $metas = $postSpec['metas'];
            $categorySlugs = isset($postSpec['category_slugs']) ? $postSpec['category_slugs'] : [];
            unset($postSpec['metas']);
            unset($postSpec['category_slugs']);

            $post = Post::create($postSpec);

            foreach ($metas as $k => $v) {
                Postmeta::create([
                    'post_id' => $post->id,
                    'key' => $k,
                    'value' => $v
                ]);
            }

            foreach ($categorySlugs as $slugKey) {
                if (isset($categoryMap[$slugKey])) {
                    \DB::table('postcategories')->insert([
                        'post_id' => $post->id,
                        'category_id' => $categoryMap[$slugKey]
                    ]);
                }
            }
        }
    }
}
