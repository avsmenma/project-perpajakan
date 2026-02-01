<?php

namespace Database\Seeders;

use App\Models\DataKaryawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder uses data generated from:
     * - Rekap Tarikan A1.csv (NPWP, Nama, Nomor Pemotongan, Kebun)
     * - Rekap PPh21 HCM.csv (NIK SAP)
     * 
     * Generated: 2026-02-01
     * Total records: 6097
     */
    public function run(): void
    {
        // Load generated data from file
        $dataFile = base_path('seeder_data_output.php');

        if (!file_exists($dataFile)) {
            $this->command->error('Seeder data file not found: ' . $dataFile);
            $this->command->info('Please run: php generate_seeder_data.php');
            return;
        }

        $data = require $dataFile;

        $this->command->info('Loading ' . count($data) . ' employee records...');

        // Truncate existing data
        DB::table('data_karyawan')->truncate();

        // Insert in chunks to avoid memory issues
        $chunkSize = 500;
        $chunks = array_chunk($data, $chunkSize);
        $bar = $this->command->getOutput()->createProgressBar(count($chunks));
        $bar->start();

        foreach ($chunks as $chunk) {
            DataKaryawan::insert($chunk);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Successfully inserted ' . count($data) . ' records.');
    }
}
