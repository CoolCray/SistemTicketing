<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
        $cols = 24;

        $seatsToInsert = [];
        foreach ($rows as $row) {
            for ($i = 1; $i <= $cols; $i++) {
                $seatsToInsert[] = [
                    'seat_number' => $row . '-' . $i,
                    'is_blocked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all 360 seats in chunks to avoid any potential parameter limit issues
        $chunks = array_chunk($seatsToInsert, 100);
        foreach ($chunks as $chunk) {
            DB::table('seats')->insert($chunk);
        }
    }
}
