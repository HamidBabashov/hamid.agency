<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Yüklənən şəkilləri WebP formatına çevirir və ölçüsünü kiçildir (GD ilə).
 * Fayllar public/uploads/{qovluq}/ altında saxlanılır (symlink tələb etmir — shared hosting üçün rahatdır).
 */
class ImageService
{
    public function store(UploadedFile $file, string $directory, ?int $maxWidth = null, ?int $quality = null): string
    {
        $maxWidth ??= config('agency.images.max_width');
        $quality ??= config('agency.images.quality');

        $source = $this->createImage($file->getRealPath(), $file->getMimeType());

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $newHeight = (int) round($height * $maxWidth / $width);
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        $relative = trim($directory, '/').'/'.date('Y/m').'/'.Str::random(24).'.webp';
        $absolute = public_path('uploads/'.$relative);
        File::ensureDirectoryExists(dirname($absolute));

        if (! imagewebp($source, $absolute, $quality)) {
            imagedestroy($source);
            throw new RuntimeException('Could not encode image as WebP.');
        }

        imagedestroy($source);

        return $relative;
    }

    public function delete(?string $path): void
    {
        if ($path && ! str_contains($path, '..')) {
            File::delete(public_path('uploads/'.$path));
        }
    }

    private function createImage(string $path, ?string $mime): \GdImage
    {
        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/gif' => @imagecreatefromgif($path),
            default => false,
        };

        if (! $image) {
            throw new RuntimeException('Unsupported image type.');
        }

        // JPEG EXIF orientasiyasını düzəlt (telefon şəkilləri)
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $orientation = @exif_read_data($path)['Orientation'] ?? 1;
            $image = match ((int) $orientation) {
                3 => imagerotate($image, 180, 0),
                6 => imagerotate($image, -90, 0),
                8 => imagerotate($image, 90, 0),
                default => $image,
            };
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }
}
