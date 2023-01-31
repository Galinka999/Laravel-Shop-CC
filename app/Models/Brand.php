<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 * @property string slug
 * @property string title
 * @property string thumbnail
 */
class Brand extends Model
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

        static::creating(function (Brand $brand) {
            $brand->slug = $brand->slug ?? str($brand->title)->slug();
        });
    }

    public function products(): hasMany
    {
        return $this->hasMany(Product::class);
    }
}
