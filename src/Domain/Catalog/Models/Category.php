<?php

namespace Domain\Catalog\Models;

use App\Models\Product;
use Domain\Catalog\Collections\CategoryCollection;
use Domain\Catalog\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Traits\Models\HasSlug;

/**
 * @mixin Builder
 * @method static Category|CategoryQueryBuilder query()
 * @property string slug
 * @property string title
 */
class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'on_home_page',
        'sorting',
    ];

    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new CategoryCollection($models);
    }
}
