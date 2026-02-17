<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;

class GenerateThumbnails
{
    private const JPG_QUALITY = 85;

    private const PIXEL_SIZES = [
        400,
        50,
    ];

    public function __construct(
        private readonly ImageManager $manager
    ) {}

    public function __invoke(UploadedFile $file, string $path, ?string $oldImage = null): string
    {
        if ($oldImage) {
            $this->deleteOldImages($oldImage);
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $fullPath = $path.$filename;

        $image = $this->manager->read($file->getPathname());

        foreach (self::PIXEL_SIZES as $size) {
            $this->generateImageBySize($image, $fullPath, $size);
        }

        return $fullPath;
    }

    public function deleteOldImages(?string $basePath): void
    {
        if (! $basePath) {
            return;
        }

        foreach (self::PIXEL_SIZES as $size) {
            $path = $this->buildPathBySize($basePath, $size);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function generateImageBySize(ImageInterface $image, string $fullPath, int $size): void
    {
        $resizedImage = $image->cover($size, $size);

        Storage::disk('public')->put(
            $this->buildPathBySize($fullPath, $size),
            $resizedImage->toJpeg(self::JPG_QUALITY)
        );
    }

    private function buildPathBySize(string $fullPath, int $size): string
    {
        $pathInfo = pathinfo($fullPath);

        return $pathInfo['dirname']
            .'/'.$size
            .'_'.$pathInfo['basename'];
    }
}
