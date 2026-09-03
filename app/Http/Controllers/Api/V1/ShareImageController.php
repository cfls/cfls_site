<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Syllabu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShareImageController extends Controller
{
    private const W = 900;

    private const H = 1200;

    public function generate(Request $request): Response
    {
        $ue = $request->string('ue')->toString();
        $score = max(0, (int) $request->input('score', 0));
        $total = max(1, (int) $request->input('total', 1));
        $themeTitle = $request->string('theme_title', '')->toString();

        $syllabus = Syllabu::where('slug', $ue)->first();
        $bgHex = $syllabus?->hex_color ?? '#6b94ff';

        [$r, $g, $b] = $this->hexToRgb($bgHex);

        $image = imagecreatetruecolor(self::W, self::H);
        imagealphablending($image, true);

        $colorBg = imagecolorallocate($image, $r, $g, $b);
        $colorCard = imagecolorallocate($image, ...$this->darken($r, $g, $b, 0.12));
        $white = imagecolorallocate($image, 255, 255, 255);
        $colorCheck = imagecolorallocate($image, ...$this->darken($r, $g, $b, 0.14));

        $bold = resource_path('fonts/arialbd.ttf');
        $regular = resource_path('fonts/arial.ttf');

        // Background + card
        imagefilledrectangle($image, 0, 0, self::W - 1, self::H - 1, $colorBg);
        imagefilledrectangle($image, 48, 48, self::W - 49, self::H - 49, $colorCard);

        // App name — centered top
        $this->textCentered($image, 38, $bold, 'lsfbgo', $white, 148);

        // Checkmark circle
        $cx = self::W / 2;
        $cy = 480;
        $rad = 155;
        imagefilledellipse($image, (int) $cx, (int) $cy, $rad * 2, $rad * 2, $white);
        imagesetthickness($image, 22);
        imageline($image, (int) ($cx - $rad * .45), (int) ($cy + $rad * .10), (int) ($cx - $rad * .05), (int) ($cy + $rad * .50), $colorCheck);
        imageline($image, (int) ($cx - $rad * .05), (int) ($cy + $rad * .50), (int) ($cx + $rad * .55), (int) ($cy - $rad * .45), $colorCheck);
        imagesetthickness($image, 1);

        // Score — large centered
        $this->textCentered($image, 108, $bold, "{$score}/{$total}", $white, 760);

        // "bonnes réponses"
        $this->textCentered($image, 42, $bold, 'bonnes réponses', $white, 832);

        // Theme name (truncated)
        if ($themeTitle !== '') {
            $label = mb_strlen($themeTitle) > 38
                ? mb_substr($themeTitle, 0, 36).'…'
                : $themeTitle;
            $this->textCentered($image, 30, $regular, $label, $white, 910);
        }

        // Bottom: site + date
        $dateStr = now()->format('d M Y');
        imagettftext($image, 26, 0, 88, 1060, $white, $regular, 'lsfbgo.be');
        [$dateW] = $this->textSize(26, $regular, $dateStr);
        imagettftext($image, 26, 0, self::W - 88 - $dateW, 1060, $white, $regular, $dateStr);

        ob_start();
        imagepng($image);
        $png = ob_get_clean();

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function textCentered(\GdImage $image, int $size, string $font, string $text, int $color, int $y): void
    {
        [$w] = $this->textSize($size, $font, $text);
        $x = (int) ((self::W - $w) / 2);
        imagettftext($image, $size, 0, $x, $y, $color, $font, $text);
    }

    private function textSize(int $size, string $font, string $text): array
    {
        $box = imagettfbbox($size, 0, $font, $text);

        return [abs($box[2] - $box[0]), abs($box[7] - $box[1])];
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function darken(int $r, int $g, int $b, float $amount): array
    {
        return [
            max(0, (int) ($r * (1 - $amount))),
            max(0, (int) ($g * (1 - $amount))),
            max(0, (int) ($b * (1 - $amount))),
        ];
    }
}
