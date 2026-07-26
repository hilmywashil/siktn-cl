<?php

namespace App\Helpers;

class WilayahHelper
{
    /**
     * Daftar 38 Provinsi Indonesia
     */
    public static function getDaftarProvinsi(): array
    {
        return [
            'Semua' => 'Semua Provinsi / Wilayah',
            'Nasional' => 'Nasional / Pengurus Pusat (PNKT)',
            'Aceh' => 'Aceh',
            'Sumatera Utara' => 'Sumatera Utara',
            'Sumatera Barat' => 'Sumatera Barat',
            'Riau' => 'Riau',
            'Kepulauan Riau' => 'Kepulauan Riau',
            'Jambi' => 'Jambi',
            'Sumatera Selatan' => 'Sumatera Selatan',
            'Bangka Belitung' => 'Bangka Belitung',
            'Bengkulu' => 'Bengkulu',
            'Lampung' => 'Lampung',
            'DKI Jakarta' => 'DKI Jakarta',
            'Jawa Barat' => 'Jawa Barat',
            'Banten' => 'Banten',
            'Jawa Tengah' => 'Jawa Tengah',
            'DI Yogyakarta' => 'DI Yogyakarta',
            'Jawa Timur' => 'Jawa Timur',
            'Bali' => 'Bali',
            'Nusa Tenggara Barat' => 'Nusa Tenggara Barat (NTB)',
            'Nusa Tenggara Timur' => 'Nusa Tenggara Timur (NTT)',
            'Kalimantan Barat' => 'Kalimantan Barat',
            'Kalimantan Tengah' => 'Kalimantan Tengah',
            'Kalimantan Selatan' => 'Kalimantan Selatan',
            'Kalimantan Timur' => 'Kalimantan Timur',
            'Kalimantan Utara' => 'Kalimantan Utara',
            'Sulawesi Utara' => 'Sulawesi Utara',
            'Gorontalo' => 'Gorontalo',
            'Sulawesi Tengah' => 'Sulawesi Tengah',
            'Sulawesi Barat' => 'Sulawesi Barat',
            'Sulawesi Selatan' => 'Sulawesi Selatan',
            'Sulawesi Tenggara' => 'Sulawesi Tenggara',
            'Maluku' => 'Maluku',
            'Maluku Utara' => 'Maluku Utara',
            'Papua' => 'Papua',
            'Papua Barat' => 'Papua Barat',
            'Papua Barat Daya' => 'Papua Barat Daya',
            'Papua Selatan' => 'Papua Selatan',
            'Papua Tengah' => 'Papua Tengah',
            'Papua Pegunungan' => 'Papua Pegunungan',
        ];
    }

    /**
     * Map string Domisili (Kabupaten/Kota) ke Provinsi
     */
    public static function getProvinsiFromDomisili(?string $domisili): string
    {
        if (!$domisili) {
            return 'Nasional';
        }

        $domisiliLower = strtolower($domisili);

        // Map keyword ke provinsi
        $mapping = [
            'jawa barat' => ['bandung', 'bogor', 'bekasi', 'depok', 'cirebon', 'sukabumi', 'tasikmalaya', 'banjar', 'cimahi', 'garut', 'cianjur', 'karawang', 'purwakarta', 'subang', 'sumedang', 'indramayu', 'majalengka', 'kuningan', 'pangandaran', 'jabar', 'jawa barat'],
            'dki jakarta' => ['jakarta', 'dki', 'kepulauan seribu'],
            'banten' => ['tangerang', 'serang', 'cilegon', 'lebak', 'pandeglang'],
            'jawa tengah' => ['semarang', 'surakarta', 'solo', 'magelang', 'pekalongan', 'salatiga', 'tegal', 'banyumas', 'purwokerto', 'cilacap', 'klaten', 'kudus', 'pati', 'jepara', 'boyolali', 'sragen', 'karanganyar', 'wonogiri', 'sukoharjo', 'grobogan', 'blora', 'rembang', 'temanggung', 'wonosobo', 'purworejo', 'kebumen', 'banjarnegara', 'purbalingga', 'brebes', 'pemalang', 'batang', 'kendals', 'jateng', 'jawa tengah'],
            'di yogyakarta' => ['yogyakarta', 'jogja', 'sleman', 'bantul', 'gunungkidul', 'kulon progo', 'diy'],
            'jawa timur' => ['surabaya', 'malang', 'kediri', 'blitar', 'madiun', 'mojokerto', 'pasuruan', 'probolinggo', 'batu', 'sidoarjo', 'gresik', 'lamongan', 'tuban', 'jombang', 'nganjuk', 'tulungagung', 'trenggalek', 'ponorogo', 'pacitan', 'magetan', 'ngawi', 'bojonegoro', 'lumajang', 'jember', 'banyuwangi', 'bondowoso', 'situbondo', 'pamekasan', 'sampang', 'sumenep', 'bangkalan', 'jatim', 'jawa timur'],
            'bali' => ['denpasar', 'badung', 'gianyar', 'tabanan', 'buleleng', 'karangasem', 'klungkung', 'bangli', 'jembrana'],
            'sumatera utara' => ['medan', 'pematangsiantar', 'sibolga', 'tanjungbalai', 'binjai', 'tebing tinggi', 'padangsidimpuan', 'gunungsitoli', 'deli serdang', 'sumut', 'sumatera utara'],
            'sumatera barat' => ['padang', 'bukittinggi', 'payakumbuh', 'solok', 'sawahlunto', 'padang panjang', 'pariaman', 'sumbar', 'sumatera barat'],
            'riau' => ['pekanbaru', 'dumai', 'kampar', 'bengkalis', 'indragiri'],
            'kepulauan riau' => ['batam', 'tanjungpinang', 'bintan', 'karimun', 'kepri'],
            'sumatera selatan' => ['palembang', 'prabumulih', 'lubuklinggau', 'pagar alam', 'sumsel', 'sumatera selatan'],
            'lampung' => ['bandar lampung', 'metro', 'lampung'],
            'sulawesi selatan' => ['makassar', 'parepare', 'palopo', 'gowa', 'bone', 'sulsel', 'sulawesi selatan'],
            'sulawesi utara' => ['manado', 'bitung', 'tomohon', 'kotamobagu', 'sulut', 'sulawesi utara'],
            'kalimantan timur' => ['samarinda', 'balikpapan', 'bontang', 'kaltim', 'kalimantan timur'],
            'kalimantan selatan' => ['banjarmasin', 'banjarbaru', 'kalsel', 'kalimantan selatan'],
            'kalimantan barat' => ['pontianak', 'singkawang', 'kalbar', 'kalimantan barat'],
            'aceh' => ['banda aceh', 'lhokseumawe', 'langsa', 'sabang', 'subulussalam', 'aceh'],
        ];

        foreach ($mapping as $prov => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($domisiliLower, $kw)) {
                    // Match to canonical province name
                    $provinces = self::getDaftarProvinsi();
                    foreach ($provinces as $key => $val) {
                        if (strtolower($key) === $prov) {
                            return $key;
                        }
                    }
                }
            }
        }

        return 'Nasional';
    }
}
