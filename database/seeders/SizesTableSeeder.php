<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = ['small', 'medium', 'large', 'xlarge'];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert(['size' => $size]);
        }
    }
}
