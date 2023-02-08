<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        $slug =  $this->slugUnique(
            str($this->{$this->slugFrom()})
                ->slug()
                ->value()
        );

        $this->{$this->slugColomn()} = $this->{$this->slugColomn()} ?? $slug;
    }

    protected function slugColomn(): string
    {
        return 'slug';
    }

    protected function slugFrom(): string
    {
        return 'title';
    }

    protected function slugUnique(string $slug): string
    {
        $originalSlug = $slug;
        $i = 0;

        while ($this->isSlugExists($originalSlug)) {
            $i++;
            $originalSlug = $originalSlug . '-' . $i;
        }

        return $originalSlug;
    }

    public function isSlugExists(string $slug): bool
    {
        $query = $this->newQuery()
            ->where($this->slugColomn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();

    }
}
