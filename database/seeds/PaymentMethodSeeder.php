<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_method')->insert([
            ['id' => 1, 'name' => 'Transfer', 'alias' => 'Transfer', 'img_path' => ' ' , 'is_active' => 1, 'created_at' => now(), 'updated_at' => null],
            ['id' => 2, 'name' => 'Cash', 'alias' => 'Tunai', 'img_path' => ' ' , 'is_active' => 1, 'created_at' => now(), 'updated_at' => null],
        ]);

    }
}
