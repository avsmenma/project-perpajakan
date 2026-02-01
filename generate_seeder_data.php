<?php
/**
 * Script to generate DataKaryawanSeeder data from CSV files
 * 
 * Usage: php generate_seeder_data.php
 * 
 * This script:
 * 1. Reads Rekap Tarikan A1.csv (NPWP, Nama, Nomor Pemotongan, Source.Name)
 * 2. Reads Rekap PPh21 HCM.csv (NIK SAP, NIK/NPWP)
 * 3. Merges data using NPWP as key
 * 4. Generates link_pdf based on PDF naming pattern
 * 5. Outputs PHP array for DataKaryawanSeeder
 */

// Configuration
$rekapTarikanPath = __DIR__ . '/Rekap Tarikan A1.csv';
$rekapHcmPath = __DIR__ . '/Rekap PPh21 HCM reno new BENER 14012026.csv';
$outputPath = __DIR__ . '/seeder_data_output.php';
$bupotBasePath = 'bupot';

// CSV delimiter (semicolon for these files)
$delimiter = ';';

echo "=== Bukti Potong A1 Seeder Data Generator ===\n\n";

// Step 1: Read Rekap PPh21 HCM to get NIK SAP by NPWP
echo "Step 1: Reading Rekap PPh21 HCM...\n";
$hcmData = [];
$hcmFile = fopen($rekapHcmPath, 'r');
if (!$hcmFile) {
    die("Error: Cannot open Rekap PPh21 HCM file\n");
}

// Skip header
$header = fgetcsv($hcmFile, 0, $delimiter);
echo "  Header: " . implode(' | ', array_slice($header, 0, 5)) . "\n";

$hcmRowCount = 0;
while (($row = fgetcsv($hcmFile, 0, $delimiter)) !== false) {
    if (count($row) < 5)
        continue;

    // Column mapping:
    // 0: NO, 1: Pers.No (NIK SAP), 2: Name, 3: Pers.Sub Area Desc., 4: NIK OK (NPWP)
    $nikSap = trim($row[1]);
    $name = trim($row[2]);
    $kebunHcm = trim($row[3]);
    $npwp = preg_replace('/[^0-9]/', '', trim($row[4])); // Clean NPWP - keep only numbers

    if (!empty($npwp) && strlen($npwp) >= 15) {
        $hcmData[$npwp] = [
            'nik_sap' => $nikSap,
            'nama_hcm' => $name,
            'kebun_hcm' => $kebunHcm,
        ];
        $hcmRowCount++;
    }
}
fclose($hcmFile);
echo "  Loaded {$hcmRowCount} records with valid NPWP\n\n";

// Step 2: Read Rekap Tarikan A1 and merge with HCM data
echo "Step 2: Reading Rekap Tarikan A1 and merging...\n";
$seederData = [];
$tarikanFile = fopen($rekapTarikanPath, 'r');
if (!$tarikanFile) {
    die("Error: Cannot open Rekap Tarikan A1 file\n");
}

// Skip header
$header = fgetcsv($tarikanFile, 0, $delimiter);
echo "  Header columns: " . count($header) . "\n";
echo "  Key columns: Source.Name[0], Nomor Pemotongan[3], NPWP[9], Nama[10]\n";

$tarikanRowCount = 0;
$matchedCount = 0;
$unmatchedCount = 0;

while (($row = fgetcsv($tarikanFile, 0, $delimiter)) !== false) {
    if (count($row) < 11)
        continue;

    // Column mapping (0-indexed):
    // 0: Source.Name (Kebun XXX.xlsx)
    // 3: Nomor Pemotongan
    // 9: NPWP
    // 10: Nama

    $sourceName = trim($row[0]);          // e.g., "Kebun Batulicin.xlsx"
    $nomorPemotongan = trim($row[3]);     // e.g., "2508L84FA"
    $npwp = preg_replace('/[^0-9]/', '', trim($row[9])); // Clean NPWP
    $nama = trim($row[10]);

    if (empty($npwp) || strlen($npwp) < 15 || empty($nomorPemotongan)) {
        continue;
    }

    $tarikanRowCount++;

    // Clean kebun name - remove .xlsx suffix and number suffix like " 2"
    $namaKebun = cleanKebunName($sourceName);

    // Map to folder name
    $folderName = mapToFolderName($namaKebun);

    // Generate PDF link
    $pdfFilename = "M_01-DOC002_Ebupot_2126_BPA1_{$nomorPemotongan}.pdf";
    $linkPdf = "{$bupotBasePath}/{$folderName}/{$pdfFilename}";

    // Get NIK SAP from HCM data
    $nikSap = null;
    if (isset($hcmData[$npwp])) {
        $nikSap = $hcmData[$npwp]['nik_sap'];
        $matchedCount++;
    } else {
        $unmatchedCount++;
    }

    // Create seeder entry
    $seederData[$npwp] = [
        'npwp' => $npwp,
        'nama' => cleanName($nama),
        'nik_sap' => $nikSap,
        'nama_kebun' => $namaKebun,
        'bupot_a1' => $nomorPemotongan,
        'link_pdf' => $linkPdf,
    ];
}
fclose($tarikanFile);

echo "  Processed {$tarikanRowCount} records from Rekap Tarikan A1\n";
echo "  Matched with NIK SAP: {$matchedCount}\n";
echo "  Without NIK SAP: {$unmatchedCount}\n\n";

// Step 3: Generate PHP output file
echo "Step 3: Generating seeder data file...\n";
$output = "<?php\n";
$output .= "/**\n";
$output .= " * Generated Seeder Data for DataKaryawan\n";
$output .= " * Generated at: " . date('Y-m-d H:i:s') . "\n";
$output .= " * Total records: " . count($seederData) . "\n";
$output .= " */\n\n";
$output .= "return [\n";

foreach ($seederData as $entry) {
    $output .= "    [\n";
    $output .= "        'npwp' => " . var_export($entry['npwp'], true) . ",\n";
    $output .= "        'nama' => " . var_export($entry['nama'], true) . ",\n";
    $output .= "        'nik_sap' => " . var_export($entry['nik_sap'], true) . ",\n";
    $output .= "        'nama_kebun' => " . var_export($entry['nama_kebun'], true) . ",\n";
    $output .= "        'bupot_a1' => " . var_export($entry['bupot_a1'], true) . ",\n";
    $output .= "        'link_pdf' => " . var_export($entry['link_pdf'], true) . ",\n";
    $output .= "    ],\n";
}

$output .= "];\n";

file_put_contents($outputPath, $output);
echo "  Generated: {$outputPath}\n";
echo "  Total seeder records: " . count($seederData) . "\n\n";

echo "=== Complete! ===\n";
echo "Next steps:\n";
echo "1. Review the generated file: seeder_data_output.php\n";
echo "2. Update DataKaryawanSeeder.php to use the generated data\n";
echo "3. Run: php artisan db:seed --class=DataKaryawanSeeder\n";

// Helper functions
function cleanKebunName($sourceName)
{
    // Remove .xlsx extension
    $name = preg_replace('/\.xlsx$/i', '', $sourceName);
    // Normalize "Kebun XXX 2" to "Kebun XXX" (merge files)
    $name = preg_replace('/\s+\d+$/', '', $name);
    return trim($name);
}

function mapToFolderName($kebunName)
{
    // Map CSV kebun names to actual folder names
    $mapping = [
        'Kebun Batulicin' => 'Kebun Batulicin',
        'Kebun Danau Salak' => 'Kebun Danau Salak',
        'Kebun Gunung Emas' => 'Kebun Gunung Emas',
        'Kebun Gunung Meliau' => 'Kebun Gunung Meliau',
        'Kebun Kembayan' => 'Kebun Kembayan',
        'Kebun Kumai' => 'Kebun Kumai',
        'Kebun Longkali' => 'Kebun Longkali',
        'Kebun Ngabang' => 'Kebun Ngabang',
        'Kebun Pamukan' => 'Kebun Pamukan',
        'Kebun Pandawa' => 'Kebun Pandawa',
        'Kebun Parindu' => 'Kebun Parindu',
        'Kebun Pelaihari' => 'Kebun Pelaihari',
        'Kebun Rimba Belian' => 'Kebun Rimba Belian',
        'Kebun Sintang' => 'Kebun Sintang',
        'Kebun Sungai Dekan' => 'Kebun Sungai Dekan',
        'Kebun Tabara' => 'Kebun Tabara',
        'Kebun Tajati' => 'Kebun Tajati',
        'Kebun Raren Batuah' => 'Kebun Raren Batuah',
        'PKR Tambarangan' => 'PKR Tambarangan',
        'PKS Gunung Meliau' => 'PKS Gunung Meliau',
        'PKS Kembayan' => 'PKS Kembayan',
        'PKS Longpinang' => 'PKS Longpinang',
        'PKS Ngabang' => 'PKS Ngabang',
        'PKS Pamukan' => 'PKS Pamukan',
        'PKS Parindu' => 'PKS Parindu',
        'PKS Pelaihari' => 'PKS Pelaihari',
        'PKS Rimba Belian' => 'PKS Rimba Belian',
        'PKS Samuntai' => 'PKS Samuntai',
        'Plasma Kalbar' => 'Plasma Kalbar',
        'Proyek Batu Bara' => 'Proyek Batu Bara',
        'Region Office PTPN IV Regional V' => 'Region Office PTPN IV Regional V',
        'Unit Group Kalimantan Timur' => 'Unit Group Kalimantan Timur',
        'Unit Group Wil Kalbar' => 'Unit Group Wil Kalbar',
        'Unit Group Wil Kalselteng' => 'Unit Group Wil Kalselteng',
    ];

    return $mapping[$kebunName] ?? $kebunName;
}

function cleanName($name)
{
    // Remove extra whitespace and tabs
    $name = preg_replace('/\s+/', ' ', $name);
    return trim($name);
}
