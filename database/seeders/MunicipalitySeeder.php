<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = DB::table('municipalities')->where('name', '=', 'ALVARO OBREGON')->first();
        if (is_null($data)) {
            DB::table('municipalities')->insert([
                'name' => 'ALVARO OBREGON',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
