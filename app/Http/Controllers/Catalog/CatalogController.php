<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')->get();

        $brands = Brand::query()
            ->select(['id', 'title','slug', 'thumbnail'])
            ->has('products')->get();

        $products = Product::search(request('s'))
            ->query(function (Builder $query)  use ($category) {
                $query->select(['id', 'title', 'slug', 'price', 'thumbnail'])
                    ->when($category->exists, function (Builder $query) use ($category) {
                        $query->whereRelation('categories', 'categories.id', '=', $category->id);
                    })
                    ->filtered()
                    ->sorted();
            })
            ->paginate(6);

        $products->map(fn(Product $product) => $product->setRelation('brands', $brands));

        return view('catalog.index', [
                'categories' => $categories,
                'brands' => $brands,
                'products' => $products,
                'category' => $category,
        ]);
    }
}
