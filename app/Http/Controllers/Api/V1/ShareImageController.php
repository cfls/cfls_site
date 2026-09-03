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

    private const MASCOT_URL = 'https://res.cloudinary.com/dmhdsjmzf/image/upload/v1787126141/good_vmzi5c.png';

    public function generate(Request $request): Response
    {
        $ue = $request->string('ue')->toString();
        $score = max(0, (int) $request->input('score', 0));
        $total = max(1, (int) $request->input('total', 1));
        $themeTitle = $request->string('theme_title', '')->toString();
        $type = $request->string('type', '')->toString();
        $themeNumber = $request->input('theme_number');
        $userName = $request->string('user_name', '')->toString();

        $syllabus = Syllabu::where('slug', $ue)->first();
        $bgHex = $request->string('hex_color', '')->toString()
            ?: ($syllabus?->hex_color ?? '#6b94ff');
        $ueTitle = $syllabus?->title ?? strtoupper($ue);

        [$r, $g, $b] = $this->hexToRgb($bgHex);

        $image = imagecreatetruecolor(self::W, self::H);
        imagealphablending($image, true);

        $colorBg = imagecolorallocate($image, $r, $g, $b);
        $colorBorder = imagecolorallocate($image, ...$this->lighten($r, $g, $b, 0.20));
        $colorCheck = imagecolorallocate($image, $r, $g, $b);
        $white = imagecolorallocate($image, 255, 255, 255);
        // Dimmed white: mix 45% bg + 55% white
        $whiteDim = imagecolorallocate($image,
            (int) ($r * 0.45 + 255 * 0.55),
            (int) ($g * 0.45 + 255 * 0.55),
            (int) ($b * 0.45 + 255 * 0.55),
        );

        $bold = resource_path('fonts/arialbd.ttf');
        $regular = resource_path('fonts/arial.ttf');

        // Background + border
        imagefilledrectangle($image, 0, 0, self::W - 1, self::H - 1, $colorBorder);
        imagefilledrectangle($image, 40, 40, self::W - 41, self::H - 41, $colorBg);

        $currentY = 70;

        // ── Mascot logo ────────────────────────────────────────────────────
        $mascot = $this->loadCachedImage('lsfbgo_mascot.png', self::MASCOT_URL);
        if ($mascot) {
            $origW = imagesx($mascot);
            $origH = imagesy($mascot);
            $scale = min(190 / $origH, 360 / $origW);
            $dstW = (int) ($origW * $scale);
            $dstH = (int) ($origH * $scale);
            $dstX = (int) ((self::W - $dstW) / 2);
            imagecopyresampled($image, $mascot, $dstX, $currentY, 0, 0, $dstW, $dstH, $origW, $origH);
            $currentY += $dstH + 40;
        } else {
            $currentY += 30;
        }

        // ── Checkmark circle ──────────────────────────────────────────────
        $cx = (int) (self::W / 2);
        $rad = 62;
        $cy = $currentY + $rad;
        imagefilledellipse($image, $cx, $cy, $rad * 2, $rad * 2, $white);
        imagesetthickness($image, 9);
        imageline($image,
            (int) ($cx - $rad * .44), (int) ($cy + $rad * .10),
            (int) ($cx - $rad * .04), (int) ($cy + $rad * .48),
            $colorCheck
        );
        imageline($image,
            (int) ($cx - $rad * .04), (int) ($cy + $rad * .48),
            (int) ($cx + $rad * .54), (int) ($cy - $rad * .42),
            $colorCheck
        );
        imagesetthickness($image, 1);
        $currentY = $cy + $rad + 130;

        // ── Score ─────────────────────────────────────────────────────────
        $this->textCentered($image, 100, $bold, "{$score}/{$total}", $white, $currentY);
        $currentY += $this->textHeight(100, $bold, "{$score}/{$total}") + 10;

        // ── "bonnes réponses" ─────────────────────────────────────────────
        $this->textCentered($image, 36, $bold, 'bonnes réponses', $white, $currentY);
        $currentY += $this->textHeight(36, $bold, 'bonnes réponses') + 28;

        // ── UE title ──────────────────────────────────────────────────────
        if ($ueTitle !== '') {
            $this->textCentered($image, 34, $bold, $ueTitle, $white, $currentY);
            $currentY += $this->textHeight(34, $bold, $ueTitle) + 14;
        }

        // ── Theme title ───────────────────────────────────────────────────
        if ($themeTitle !== '') {
            $label = mb_strlen($themeTitle) > 36 ? mb_substr($themeTitle, 0, 34).'…' : $themeTitle;
            $this->textCentered($image, 26, $regular, $label, $white, $currentY);
            $currentY += $this->textHeight(26, $regular, $label) + 10;
        }

        // ── Type · Thème N (dimmed) ───────────────────────────────────────
        $badge = $this->typeBadge($type, $themeNumber);
        if ($badge !== '') {
            $this->textCentered($image, 22, $regular, $badge, $whiteDim, $currentY);
        }

        // ── Bottom: username (left) | date (right) ────────────────────────
        $bottomY = self::H - 52;
        $margin = 70;
        $dateStr = now()->format('d M Y');

        if ($userName !== '') {
            imagettftext($image, 24, 0, $margin, $bottomY, $white, $regular, $userName);
        }

        [$dateW] = $this->textSize(24, $regular, $dateStr);
        imagettftext($image, 24, 0, self::W - $margin - $dateW, $bottomY, $white, $regular, $dateStr);

        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-store',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function typeBadge(string $type, mixed $number): string
    {
        $label = match ($type) {
            'text' => 'Texte',
            'choice' => 'Choix multiple',
            'match' => 'Appariement',
            'video-choice' => 'Choix vidéo',
            'word-choice' => 'Signe mot',
            'yes-no' => 'Oui / Non',
            default => '',
        };

        if ($label === '') {
            return '';
        }

        return $number !== null ? "{$label}  ·  Thème {$number}" : $label;
    }

    private function loadCachedImage(string $filename, string $url): \GdImage|false
    {
        $path = sys_get_temp_dir().'/'.$filename;

        if (! file_exists($path)) {
            $data = @file_get_contents($url);
            if (! $data) {
                return false;
            }

            file_put_contents($path, $data);
        }

        $raw = @file_get_contents($path);

        return $raw ? @imagecreatefromstring($raw) : false;
    }

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

    private function textHeight(int $size, string $font, string $text): int
    {
        return $this->textSize($size, $font, $text)[1];
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    private function lighten(int $r, int $g, int $b, float $amount): array
    {
        return [
            min(255, (int) ($r + (255 - $r) * $amount)),
            min(255, (int) ($g + (255 - $g) * $amount)),
            min(255, (int) ($b + (255 - $b) * $amount)),
        ];
    }
}
