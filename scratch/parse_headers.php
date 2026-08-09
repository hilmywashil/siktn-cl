<?php
$xml = simplexml_load_file(__DIR__ . '/surat_xlsx/xl/sharedStrings.xml');
$strings = [];
foreach($xml->si as $si) {
    $t = '';
    if (isset($si->t)) $t = (string)$si->t;
    if (isset($si->r)) foreach($si->r as $r) $t .= (string)$r->t;
    $strings[] = $t;
}

// Read worksheet to find row 1 (header)
$wsXml = simplexml_load_file(__DIR__ . '/surat_xlsx/xl/worksheets/sheet1.xml');
$wsXml->registerXPathNamespace('x', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

$rows = $wsXml->xpath('//x:row');
$headerRow = null;
foreach ($rows as $row) {
    if ((string)$row['r'] === '1') { $headerRow = $row; break; }
}

echo "=== HEADER ROW CELLS ===\n";
if ($headerRow) {
    foreach ($headerRow->c as $cell) {
        $cellRef = (string)$cell['r'];
        $type = (string)$cell['t'];
        $v = (string)$cell->v;
        if ($type === 's') {
            echo "$cellRef: " . ($strings[(int)$v] ?? '???') . "\n";
        } else {
            echo "$cellRef: $v\n";
        }
    }
} else {
    echo "Row 1 not found, checking first 5 rows:\n";
    foreach (array_slice($rows, 0, 5) as $row) {
        echo "Row " . $row['r'] . ":\n";
        foreach ($row->c as $cell) {
            $cellRef = (string)$cell['r'];
            $type = (string)$cell['t'];
            $v = (string)$cell->v;
            if ($type === 's') {
                echo "  $cellRef: " . ($strings[(int)$v] ?? '???') . "\n";
            } else {
                echo "  $cellRef: $v\n";
            }
        }
    }
}
