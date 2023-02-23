<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): Factory|View|Application
    {
        $categories = Category::query()->homePage()->get();

        $brands = Brand::query()->homePage()->get();

        $products = Product::query()->homepage()->get();

        return view('index', compact(
            'categories',
            'brands',
            'products'
        ));
    }
}
