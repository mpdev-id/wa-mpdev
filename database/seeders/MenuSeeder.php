<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['en', 'id'] as $locale) {
            Menu::updateOrCreate(
                ['position' => 'main-menu', 'lang' => $locale],
                [
                    'name' => 'Header',
                    'data' => '[{"text":"Beranda","icon":"","href":"/","target":"_self","title":""},{"text":"Harga","icon":"empty","href":"/pricing","target":"_self","title":""},{"text":"Tentang Kami","icon":"empty","href":"/about","target":"_self","title":""},{"text":"Fitur","icon":"empty","href":"/features","target":"_self","title":""},{"text":"Blog","icon":"empty","href":"/blogs","target":"_self","title":""},{"text":"Kontak","icon":"empty","href":"/contact","target":"_self","title":""}]',
                    'status' => 1
                ]
            );
        }
    }
}
