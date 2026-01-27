<?php

namespace Database\Seeders;

use App\Models\DataKaryawan;
use Illuminate\Database\Seeder;

class DataKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'npwp' => '6123030802050002',
                'nama' => 'Ahmad Suryadi',
                'nik_sap' => 'SAP001234',
                'nama_kebun' => 'Kebun Sawit Jaya',
                'bupot_a1' => 'BP-A1-2026-001',
                'link_pdf' => 'bupot/BP-A1-2026-001.pdf',
            ],
            [
                'npwp' => '02.345.678.9-012.345',
                'nama' => 'Budi Santoso',
                'nik_sap' => 'SAP002345',
                'nama_kebun' => 'Kebun Makmur Sejahtera',
                'bupot_a1' => 'BP-A1-2026-002',
                'link_pdf' => 'bupot/BP-A1-2026-002.pdf',
            ],
            [
                'npwp' => '03.456.789.0-123.456',
                'nama' => 'Citra Dewi Lestari',
                'nik_sap' => 'SAP003456',
                'nama_kebun' => 'Kebun Harapan Baru',
                'bupot_a1' => 'BP-A1-2026-003',
                'link_pdf' => 'bupot/BP-A1-2026-003.pdf',
            ],
            [
                'npwp' => '04.567.890.1-234.567',
                'nama' => 'Dedi Prasetyo',
                'nik_sap' => 'SAP004567',
                'nama_kebun' => 'Kebun Sawit Jaya',
                'bupot_a1' => 'BP-A1-2026-004',
                'link_pdf' => null,
            ],
            [
                'npwp' => '05.678.901.2-345.678',
                'nama' => 'Eko Widodo',
                'nik_sap' => 'SAP005678',
                'nama_kebun' => 'Kebun Maju Bersama',
                'bupot_a1' => 'BP-A1-2026-005',
                'link_pdf' => 'bupot/BP-A1-2026-005.pdf',
            ],
            [
                'npwp' => '06.789.012.3-456.789',
                'nama' => 'Fitri Handayani',
                'nik_sap' => 'SAP006789',
                'nama_kebun' => 'Kebun Makmur Sejahtera',
                'bupot_a1' => 'BP-A1-2026-006',
                'link_pdf' => null,
            ],
            [
                'npwp' => '07.890.123.4-567.890',
                'nama' => 'Gunawan Setiawan',
                'nik_sap' => 'SAP007890',
                'nama_kebun' => 'Kebun Harapan Baru',
                'bupot_a1' => 'BP-A1-2026-007',
                'link_pdf' => 'bupot/BP-A1-2026-007.pdf',
            ],
            [
                'npwp' => '08.901.234.5-678.901',
                'nama' => 'Hendra Kusuma',
                'nik_sap' => 'SAP008901',
                'nama_kebun' => 'Kebun Sawit Jaya',
                'bupot_a1' => 'BP-A1-2026-008',
                'link_pdf' => 'bupot/BP-A1-2026-008.pdf',
            ],
        ];

        foreach ($data as $karyawan) {
            DataKaryawan::create($karyawan);
        }
    }
}
