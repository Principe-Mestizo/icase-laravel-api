<?php

namespace App\Http\Controllers;

use App\Models\Product\Categorie;
use App\Models\Product\Product;


class SalesController extends Controller
{
    public function getTotals()
    {
        // Get total products count
        $totalProducts = Product::count();

        // Get total categories count
        $totalCategories = Categorie::count();

        // Return the totals
        return response()->json([
            'total_products' => $totalProducts,
            'total_categories' => $totalCategories
        ]);
    }

}
