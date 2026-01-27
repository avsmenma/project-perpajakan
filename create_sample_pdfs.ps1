Add-Type -AssemblyName System.Drawing

# Function to create a simple PDF-like document (actually creating a text file with PDF structure)
function Create-SamplePDF {
    param(
        [string]$filename,
        [string]$npwp = "",
        [string]$nama = "",
        [string]$nikSap = "",
        [string]$namaKebun = ""
    )
    
    $pdfPath = "storage\app\public\bupot\$filename"
    
    # Create a basic PDF structure (simplified)
    $pdfContent = @"
%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj
2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj
3 0 obj
<<
/Type /Page
/Parent 2 0 R
/Resources <<
/Font <<
/F1 4 0 R
>>
>>
/MediaBox [0 0 612 792]
/Contents 5 0 R
>>
endobj
4 0 obj
<<
/Type /Font
/Subtype /Type1
/BaseFont /Helvetica
>>
endobj
5 0 obj
<<
/Length 800
>>
stream
BT
/F1 16 Tf
150 700 Td
(BUKTI PEMOTONGAN PAJAK) Tj
0 -20 Td
/F1 12 Tf
(Formulir A1) Tj
0 -40 Td
/F1 10 Tf
(Nomor Dokumen: $filename) Tj
0 -30 Td
(NPWP: $npwp) Tj
0 -20 Td
(Nama Wajib Pajak: $nama) Tj
0 -20 Td
(NIK/SAP: $nikSap) Tj
0 -20 Td
(Nama Kebun: $namaKebun) Tj
0 -40 Td
(RINCIAN PENGHASILAN DAN PEMOTONGAN PAJAK) Tj
0 -30 Td
(No.  | Uraian Penghasilan          | Jumlah Bruto | Tarif | PPh Dipotong) Tj
0 -20 Td
(1.   | Gaji/Upah/Honorarium         | Rp -         | 10%   | Rp -) Tj
0 -20 Td
(2.   | Tunjangan                    | Rp -         | 20%   | Rp -) Tj
0 -20 Td
(3.   | Penghasilan                  | Rp -         | 10%   | Rp -) Tj
0 -40 Td
(Tanda Tangan Pemotong Pajak:          Tanda Tangan Penerima Penghasilan:) Tj
0 -30 Td
(Data, .../.../....                     Data, .../.../....) Tj
ET
endstream
endobj
xref
0 6
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000262 00000 n
0000000341 00000 n
trailer
<<
/Size 6
/Root 1 0 R
>>
startxref
1200
%%EOF
"@
    
    $pdfContent | Out-File -FilePath $pdfPath -Encoding ASCII
    Write-Host "Created: $filename"
}

# Create sample PDFs for each employee in the seeder
Create-SamplePDF -filename "BP-A1-2026-001.pdf" -npwp "6123030802050002" -nama "Ahmad Suryadi" -nikSap "SAP001234" -namaKebun "Kebun Sawit Jaya"
Create-SamplePDF -filename "BP-A1-2026-002.pdf" -npwp "02.345.678.9-012.345" -nama "Budi Santoso" -nikSap "SAP002345" -namaKebun "Kebun Makmur Sejahtera"
Create-SamplePDF -filename "BP-A1-2026-003.pdf" -npwp "03.456.789.0-123.456" -nama "Citra Dewi Lestari" -nikSap "SAP003456" -namaKebun "Kebun Harapan Baru"
Create-SamplePDF -filename "BP-A1-2026-005.pdf" -npwp "05.678.901.2-345.678" -nama "Eko Widodo" -nikSap "SAP005678" -namaKebun "Kebun Maju Bersama"
Create-SamplePDF -filename "BP-A1-2026-007.pdf" -npwp "07.890.123.4-567.890" -nama "Gunawan Setiawan" -nikSap "SAP007890" -namaKebun "Kebun Harapan Baru"
Create-SamplePDF -filename "BP-A1-2026-008.pdf" -npwp "08.901.234.5-678.901" -nama "Hendra Kusuma" -nikSap "SAP008901" -namaKebun "Kebun Sawit Jaya"

Write-Host "`nAll sample PDF files have been created successfully!"
