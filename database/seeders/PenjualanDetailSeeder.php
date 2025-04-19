<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_penjualan_detail')->insert([
            ['penjualan_id' => 1, 'barang_id' => 1, 'harga' => 6000000, 'jumlah' => 1],
            ['penjualan_id' => 1, 'barang_id' => 3, 'harga' => 150000, 'jumlah' => 2],
            ['penjualan_id' => 1, 'barang_id' => 5, 'harga' => 20000, 'jumlah' => 3],
            ['penjualan_id' => 2, 'barang_id' => 2, 'harga' => 2500000, 'jumlah' => 1],
            ['penjualan_id' => 2, 'barang_id' => 4, 'harga' => 200000, 'jumlah' => 2],
            ['penjualan_id' => 2, 'barang_id' => 6, 'harga' => 15000, 'jumlah' => 3],
            ['penjualan_id' => 3, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 4],
            ['penjualan_id' => 3, 'barang_id' => 9, 'harga' => 7000, 'jumlah' => 5],
            ['penjualan_id' => 3, 'barang_id' => 1, 'harga' => 6000000, 'jumlah' => 1],
            ['penjualan_id' => 4, 'barang_id' => 3, 'harga' => 150000, 'jumlah' => 2],
            ['penjualan_id' => 4, 'barang_id' => 5, 'harga' => 20000, 'jumlah' => 3],
            ['penjualan_id' => 4, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 4],
            ['penjualan_id' => 5, 'barang_id' => 9, 'harga' => 7000, 'jumlah' => 5],
            ['penjualan_id' => 5, 'barang_id' => 2, 'harga' => 2500000, 'jumlah' => 1],
            ['penjualan_id' => 5, 'barang_id' => 4, 'harga' => 200000, 'jumlah' => 2],
            ['penjualan_id' => 6, 'barang_id' => 6, 'harga' => 15000, 'jumlah' => 3],
            ['penjualan_id' => 6, 'barang_id' => 8, 'harga' => 6000, 'jumlah' => 4],
            ['penjualan_id' => 6, 'barang_id' => 10, 'harga' => 3000, 'jumlah' => 5],
            ['penjualan_id' => 7, 'barang_id' => 1, 'harga' => 6000000, 'jumlah' => 1],
            ['penjualan_id' => 7, 'barang_id' => 3, 'harga' => 150000, 'jumlah' => 2],
            ['penjualan_id' => 7, 'barang_id' => 5, 'harga' => 20000, 'jumlah' => 3],
            ['penjualan_id' => 8, 'barang_id' => 2, 'harga' => 2500000, 'jumlah' => 1],
            ['penjualan_id' => 8, 'barang_id' => 4, 'harga' => 200000, 'jumlah' => 2],
            ['penjualan_id' => 8, 'barang_id' => 6, 'harga' => 15000, 'jumlah' => 3],
            ['penjualan_id' => 9, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 4],
            ['penjualan_id' => 9, 'barang_id' => 9, 'harga' => 7000, 'jumlah' => 5],
            ['penjualan_id' => 9, 'barang_id' => 1, 'harga' => 6000000, 'jumlah' => 1],
            ['penjualan_id' => 10, 'barang_id' => 3, 'harga' => 150000, 'jumlah' => 2],
            ['penjualan_id' => 10, 'barang_id' => 5, 'harga' => 20000, 'jumlah' => 3],
            ['penjualan_id' => 10, 'barang_id' => 7, 'harga' => 5000, 'jumlah' => 4],
        ]);
    }
}
