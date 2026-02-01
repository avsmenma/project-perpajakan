<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$count = DB::table('data_karyawan')->count();
echo "Total records in data_karyawan: {$count}\n\n";

// Sample first 5 records
$samples = DB::table('data_karyawan')->take(5)->get();
echo "Sample records:\n";
foreach ($samples as $row) {
    echo "- NPWP: {$row->npwp}\n";
    echo "  Nama: {$row->nama}\n";
    echo "  NIK SAP: {$row->nik_sap}\n";
    echo "  Kebun: {$row->nama_kebun}\n";
    echo "  Bupot: {$row->bupot_a1}\n";
    echo "  Link PDF: {$row->link_pdf}\n\n";
}

// Check if PDF files exist
echo "Verifying PDF files exist:\n";
foreach ($samples as $row) {
    $fullPath = storage_path('app/public/' . $row->link_pdf);
    $exists = file_exists($fullPath) ? 'YES' : 'NO';
    echo "- {$row->link_pdf}: {$exists}\n";
}
