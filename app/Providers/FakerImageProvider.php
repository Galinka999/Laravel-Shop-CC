<?php

declare(strict_types=1);

namespace App\Providers;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageProvider extends Base
{
    public function getUmageLoremFlickr($dir, int $width = 500, int $height = 500): string
    {
        $this->checkDirectory($dir);

        $filename = $dir . '/' . Str::random(6). '.jpg';

        Storage::put(
            $filename,
            file_get_contents("https://loremflickr.com/$width/$height")
        );
        return '/storage/' . $filename;
    }

    public function getUmageFromFixTures($dir): string
    {
        $this->checkDirectory($dir);

        $filename = $this->generator->file(base_path('/tests/FixTures/' . $dir), storage_path('/app/public/' . $dir), false);

        return '/storage/app/public/'. $dir . '/' . $filename;
    }

    protected function checkDirectory($dir)
    {
        try {
            if(Storage::directoryMissing($dir)) {
                Storage::createDirectory($dir);
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
