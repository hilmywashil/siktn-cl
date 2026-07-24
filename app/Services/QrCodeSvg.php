<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeSvg
{
    /**
     * Generate authentic, scannable, high-quality SVG QR Code markup.
     */
    public static function generate(string $text): string
    {
        try {
            $options = new QROptions([
                'outputBase64' => false,
            ]);

            $svg = (new QRCode($options))->render($text);

            // Clean up XML header & add inline responsive styling
            $svg = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $svg);
            $svg = preg_replace('/<svg([^>]*)>/i', '<svg $1 style="width: 100%; height: 100%; display: block;">', $svg, 1);

            return $svg;
        } catch (\Throwable $e) {
            return '';
        }
    }
}
