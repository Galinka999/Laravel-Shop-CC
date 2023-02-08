<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Stringable;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug
                ?? self::getSlug($model);
        });
    }

    public static function getSlug(Model$model)
    {
        $slugs = DB::table($model->getTable())->pluck('slug');

        $field = $model->{self::slugFrom()};
        $slug = str($field)->slug();

        for($i = 1; $i < 1000; $i++) {
            if(!in_array($slug, $slugs->toArray())) {
                return $slug;
            }
            $slug = str($field)
                ->slug()
                ->append('-' . $i);
        }

        return $slug;
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
