<?php
$provinces = json_decode(file_get_contents('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json'), true);
$regencies = [];
$provincesName = [];

foreach($provinces as $prov) {
    $provincesName[] = ucwords(strtolower($prov['name']));
    
    $regs = json_decode(file_get_contents('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/'.$prov['id'].'.json'), true);
    foreach($regs as $reg) {
        $regencies[] = ucwords(strtolower($reg['name']));
    }
}

file_put_contents('public/provinces.json', json_encode($provincesName, JSON_PRETTY_PRINT));
file_put_contents('public/regencies.json', json_encode($regencies, JSON_PRETTY_PRINT));
echo "Done";
