<?php

namespace Database\Seeders;

use App\Models\GroundRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroundRegistriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GroundRegistry::truncate();
        $csvData = fopen(base_path('database/data/catastro2021_ALVARO_OBREGON.csv'), 'r');
        $firstLine = true;
        $municipality = DB::table('municipalities')->where('name', '=', 'ALVARO OBREGON')->first();

        $limitColumns = 10000;
        $count = 0;
        $batchSize = 1000;
        $dataBatch = [];

        while (($data = fgetcsv($csvData, 1000, ',')) !== false && $count < $limitColumns) {

            if (!$firstLine) {
                $dataBatch[] = [
                    'municipality_id' => $municipality->id,
                    'fid' => $data[0],
                    'fid_2' => $data[1],
                    'street_number' => $data[2],
                    'zip_code' => (int) $data[3],
                    'colony' => $data[4],
                    'ground_surface' => $data[6],
                    'construction_surface' => $data[7],
                    'year_construction' => (int) $data[8],
                    'special_installation' => $data[9] == 1,
                    'ground_unit_value' => $data[10],
                    'ground_value' => $data[11],
                    'cve_vus' => $data[12],
                    'subsidy' => $data[13],
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if (count($dataBatch) >= $batchSize) {
                    GroundRegistry::insert($dataBatch);
                    $dataBatch = [];
                }
                $count++;
            }
            $firstLine = false;
        }

        if (!empty($dataBatch)) {
            GroundRegistry::insert($dataBatch);
        }

    }
}
