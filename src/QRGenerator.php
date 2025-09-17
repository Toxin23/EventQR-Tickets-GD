<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QRGenerator {
    public static function generate(string $code): string {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($code)
            ->size(300)
            ->margin(10)
            ->build();

        $path = 'public/qrcodes/' . $code . '.png';
        $result->saveToFile($path);

        return 'qrcodes/' . $code . '.png';
    }
}
