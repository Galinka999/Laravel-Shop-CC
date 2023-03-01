<?php

declare(strict_types=1);

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageProvider extends Base
{
    protected $nameFolder = 'images';

    public function getUmageLoremFlickr(string $dir, int $width = 500, int $height = 500): string
    {
        $this->checkDirectory($dir);

        $filename = $dir . '/' . Str::random(6). '.jpg';

        Storage::put(
            $filename,
            file_get_contents("https://loremflickr.com/$width/$height")
        );
        return '/storage/' . $filename;
    }

    public function getUmageFromFixTures(string $dir): string
    {
        $storage = $this->getStorageDisk();

        $this->checkDirectory($dir);

        $filename = $this->generator->file(
            base_path("/tests/FixTures/images/$dir"),
            $storage->path($dir),
            false
        );

        return "/storage/{$this->nameFolder}/". trim($dir . '/') . '/' . $filename;
    }

    protected function getStorageDisk(): Filesystem
    {
        return Storage::disk($this->nameFolder);
    }

    protected function checkDirectory(string $dir)
    {
        $storage = $this->getStorageDisk();

        try {
            if(!$storage->exists($dir)) {
                $storage->makeDirectory($dir);
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
