<?php
declare(strict_types=1);

namespace App;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class QRGenerator {
    public static function generate(string $ticketCode): string {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($ticketCode)
            ->size(300)
            ->margin(10)
            ->build();

        $filePath = __DIR__ . '/../public/qrcodes/' . $ticketCode . '.png';
        $result->saveToFile($filePath);

        return $filePath;
    }
}
