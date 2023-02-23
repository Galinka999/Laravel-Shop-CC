<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use App\Models\Product;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): Factory|View|Application
    {
        $categories = CategoryViewModel::make()->homePage();

        $brands = Brand::query()->homePage()->get();

        $products = Product::query()->homePage()->get();

        return view('index', compact(
            'categories',
            'brands',
            'products'
        ));
    }
}
