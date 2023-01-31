<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PhpParser\Builder;

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

    protected $fillable = [
        'slug',
        'title',
        'thumbnail'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Product $product) {
            $product->slug = $product->slug ?? str($product->title)->slug();
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
}
