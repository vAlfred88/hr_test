<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use \App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::when($request->has('properties'), function ($query) use ($request) {
            return $query->filterByProperties($request->query('properties'));
        })->paginate(40);
        
        return new ProductResource($products);
    }
}
