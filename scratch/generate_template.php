<?php
/**
 * Generate Template_Import_Surat_Masuk.xlsx
 * Kolom sesuai SURAT MASUK.xlsx asli:
 * No. | TANGGAL DITERIMA | PENGIRIM | PERIHAL | NO SURAT | ARSIP PDF (link) | SURAT BALASAN
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Surat Masuk');

// === HEADER ROW ===
$headers = [
    'A1' => 'No.',
    'B1' => 'TANGGAL DITERIMA',
    'C1' => 'PENGIRIM',
    'D1' => 'PERIHAL',
    'E1' => 'NO SURAT',
    'F1' => 'ARSIP PDF',
    'G1' => 'SURAT BALASAN',
];

foreach ($headers as $cell => $label) {
    $sheet->setCellValue($cell, $label);
}

// Style header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'B7830F'],
        'size' => 11,
        'name' => 'Calibri',
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '022648'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'AAAAAA'],
        ],
    ],
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
$sheet->getRowDimension(1)->setRowHeight(28);

// === EXAMPLE ROWS ===
$rows = [
    [1, '09/08/2026', 'PPKT JATENG', 'Tembusan Pemberitahuan TKKT Grobogan tidak sah', '014/KT-PPJT/II/2026', 'https://drive.google.com/file/d/example1/view', ''],
    [2, '09/08/2026', 'UMJ', 'PERMOHONAN STUDI MAHASISWA', '222/F.1-UMJ/IV/2026', 'https://drive.google.com/file/d/example2/view', 'ada surat balasan'],
    [3, '09/08/2026', 'DNIKS', 'Undangan Peringatan HUT Ke-59 DNIKS', '0436/A-1/DNIKS/VII/2026', '', ''],
];

$rowStyle = [
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'DDDDDD'],
        ],
    ],
];

foreach ($rows as $i => $row) {
    $r = $i + 2;
    $sheet->fromArray($row, null, 'A' . $r);
    $sheet->getStyle('A' . $r . ':G' . $r)->applyFromArray($rowStyle);
    $bgColor = $i % 2 === 0 ? 'F9FAFB' : 'FFFFFF';
    $sheet->getStyle('A' . $r . ':G' . $r)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
    $sheet->getRowDimension($r)->setRowHeight(22);
}

// === COLUMN WIDTHS ===
$widths = ['A' => 5, 'B' => 18, 'C' => 28, 'D' => 42, 'E' => 28, 'F' => 40, 'G' => 25];
foreach ($widths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// === FREEZE TOP ROW ===
$sheet->freezePane('A2');

// === NOTES ROW ===
$sheet->setCellValue('A' . (count($rows) + 3), '* ARSIP PDF: isi dengan link Google Drive ke berkas PDF surat');
$sheet->getStyle('A' . (count($rows) + 3) . ':G' . (count($rows) + 3))->applyFromArray([
    'font' => ['italic' => true, 'color' => ['rgb' => '6B7280'], 'size' => 9],
]);
$sheet->mergeCells('A' . (count($rows) + 3) . ':G' . (count($rows) + 3));

$sheet->setCellValue('A' . (count($rows) + 4), '* SURAT BALASAN: isi keterangan jika ada surat balasan yang dikirimkan');
$sheet->getStyle('A' . (count($rows) + 4) . ':G' . (count($rows) + 4))->applyFromArray([
    'font' => ['italic' => true, 'color' => ['rgb' => '6B7280'], 'size' => 9],
]);
$sheet->mergeCells('A' . (count($rows) + 4) . ':G' . (count($rows) + 4));

$sheet->setCellValue('A' . (count($rows) + 5), '* TANGGAL DITERIMA: format DD/MM/YYYY atau YYYY-MM-DD');
$sheet->getStyle('A' . (count($rows) + 5) . ':G' . (count($rows) + 5))->applyFromArray([
    'font' => ['italic' => true, 'color' => ['rgb' => '6B7280'], 'size' => 9],
]);
$sheet->mergeCells('A' . (count($rows) + 5) . ':G' . (count($rows) + 5));

// === WRITE FILE ===
$outputPath = __DIR__ . '/../public/templates/Template_Import_Surat_Masuk.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($outputPath);
echo "File generated: $outputPath\n";
echo "File size: " . filesize($outputPath) . " bytes\n";
