<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

/**
 * @mixin Builder
 * @property string slug
 * @property string title
 * @property string thumbnail
 * @property integer brand_id
 * @property integer price
 */
class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = [
        'slug',
        'title',
        'brand_id',
        'price',
        'thumbnail',
        'on_home_page',
        'sorting',
        'text',
    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];

    protected function thumbnailDir(): string
    {
        return 'products';
    }

    #[SearchUsingFullText(['title', 'text'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
        ];
    }

    public function scopeFiltered(Builder $query)
    {
        $query->when(request('filters.brands'), function (Builder $q) {
            $q->whereIn('brand_id', request('filters.brands'));
        })->when(request('filters.price'), function (Builder $q) {
            $q->whereBetween('price', [
                request('filters.price.from', 0)* 100,
                request('filters.price.to', 10000) *100
            ]);
        });
    }

    public function scopeSorted(Builder $query)
    {
        $query->when(request('sort'), function (Builder $q) {
            $column = request()->str('sort');

            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $q->orderBy((string)$column->remove('-'), $direction);
            }
        });
    }

    public function brand(): belongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)->orderBy('sorting')->limit(6);
    }

}
