<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Traits\Models\HasSlug;

/**
 * @mixin Builder
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

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)->orderBy('sorting')->limit(6);
    }
}
