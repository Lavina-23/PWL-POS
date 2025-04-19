<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'pembeli' => 'Budi',
                'penjualan_kode' => 'PJL001',
                'penjualan_tanggal' => now()
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Ani',
                'penjualan_kode' => 'PJL002',
                'penjualan_tanggal' => now()->addMinutes(15)
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Caca',
                'penjualan_kode' => 'PJL003',
                'penjualan_tanggal' => now()->addMinutes(30)
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Dedi',
                'penjualan_kode' => 'PJL004',
                'penjualan_tanggal' => now()->addMinutes(45)
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Eka',
                'penjualan_kode' => 'PJL005',
                'penjualan_tanggal' => now()->addHour()
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Fafa',
                'penjualan_kode' => 'PJL006',
                'penjualan_tanggal' => now()->addHour()->addMinutes(15)
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Gigi',
                'penjualan_kode' => 'PJL007',
                'penjualan_tanggal' => now()->addHour()->addMinutes(30)
            ],
            [
                'user_id' => 2,
                'pembeli' => 'Hani',
                'penjualan_kode' => 'PJL008',
                'penjualan_tanggal' => now()->addHour()->addMinutes(45)
            ],
            [
                'user_id' => 3,
                'pembeli' => 'Iwan',
                'penjualan_kode' => 'PJL009',
                'penjualan_tanggal' => now()->addHours(2)
            ],
            [
                'user_id' => 1,
                'pembeli' => 'Joko',
                'penjualan_kode' => 'PJL010',
                'penjualan_tanggal' => now()->addHours(2)->addMinutes(15)
            ],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}
