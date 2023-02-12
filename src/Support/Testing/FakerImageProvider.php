<?php

declare(strict_types=1);

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageProvider extends Base
{
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

    public function getUmageFromFixTures(string $fixturesDir, string $storageDir): string
    {
        $this->checkDirectory($storageDir);

        $filename = $this->generator->file(
            base_path("/tests/FixTures/images/$fixturesDir"),
            Storage::path($storageDir),
            false
        );

        return '/storage/'. trim($storageDir . '/') . '/' . $filename;
    }

    protected function checkDirectory(string $dir)
    {
        try {
            if(!Storage::exists($dir)) {
                Storage::makeDirectory($dir);
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
