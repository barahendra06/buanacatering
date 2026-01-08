<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CateringOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catering_order_status')->insert([
            ['id' => 1, 'name' => 'Initial', 'created_at' => now(), 'updated_at' => null],
            ['id' => 2, 'name' => 'Waiting Payment', 'created_at' => now(), 'updated_at' => null],
            ['id' => 3, 'name' => 'Waiting Confirmation', 'created_at' => now(), 'updated_at' => null],
            ['id' => 4, 'name' => 'Packing', 'created_at' => now(), 'updated_at' => null],
            ['id' => 5, 'name' => 'On Delivery', 'created_at' => now(), 'updated_at' => null],
            ['id' => 6, 'name' => 'Delivered', 'created_at' => now(), 'updated_at' => null],
            ['id' => 7, 'name' => 'Completed', 'created_at' => now(), 'updated_at' => null],
            ['id' => 8, 'name' => 'Cancelled', 'created_at' => now(), 'updated_at' => null],
            ['id' => 9, 'name' => 'Return', 'created_at' => now(), 'updated_at' => null],
            ['id' => 10, 'name' => 'Refund', 'created_at' => now(), 'updated_at' => null],
            ['id' => 11, 'name' => 'Expired', 'created_at' => now(), 'updated_at' => null],
        ]);

    }
}
