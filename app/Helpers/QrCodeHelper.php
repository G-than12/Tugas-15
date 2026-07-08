<?php

namespace App\Helpers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Helper tipis untuk generate QR Code sebagai string SVG.
 *
 * Dipakai sebagai pengganti simplesoftwareio/simple-qrcode, karena package
 * tersebut masih mensyaratkan bacon/bacon-qr-code ^2, sedangkan Laravel versi
 * baru (termasuk paket seperti Fortify) sudah menggunakan bacon/bacon-qr-code ^3.
 * Class ini memakai bacon/bacon-qr-code secara langsung sehingga kompatibel.
 */
class QrCodeHelper
{
    /**
     * Generate QR Code sebagai string SVG mentah.
     *
     * @param string $text Teks/kode yang akan di-encode (mis. kode_transaksi)
     * @param int    $size Ukuran QR code dalam pixel (lebar = tinggi)
     * @param int    $margin Margin putih di sekeliling QR code
     * @return string String SVG (bisa langsung di-echo dengan {!! !!} di Blade)
     */
    public static function generateSvg(string $text, int $size = 200, int $margin = 1): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($text);
    }

    /**
     * Generate QR Code sebagai data URI base64 (opsional, jika ingin dipakai
     * di dalam tag <img src="..."> alih-alih inline SVG).
     */
    public static function generateDataUri(string $text, int $size = 200, int $margin = 1): string
    {
        $svg = self::generateSvg($text, $size, $margin);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
