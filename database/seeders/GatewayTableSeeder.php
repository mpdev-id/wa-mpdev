<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gateway;
use Illuminate\Support\Facades\Schema;

class GatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing gateways with foreign key checks disabled
        Schema::disableForeignKeyConstraints();
        Gateway::truncate();
        Schema::enableForeignKeyConstraints();

        $gateways = [
            [
                'id' => '1',
                'name' => 'QRIS',
                'logo' => '/uploads/payment-gateway/qris.png',
                'min_amount' => 1000, 
                'max_amount' => -1,
                'charge' => '0',
                'namespace' => 'App\\Gateway\\CustomGateway',
                'is_auto' => '0',
                'image_accept' => '1',
                'test_mode' => '0',
                'status' => '1',
                'phone_required' => '0',
                'data' => null,
                'currency' => 'IDR',
                'comment' => 'Silakan lakukan pembayaran dengan memindai kode QRIS WHASAPI di atas. Setelah pembayaran sukses, unggah bukti transfer Anda di form di samping/bawah dan isi catatan konfirmasi pembayaran Anda agar admin kami dapat segera memproses langganan Anda secara manual.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        Gateway::insert($gateways);
    }
}
