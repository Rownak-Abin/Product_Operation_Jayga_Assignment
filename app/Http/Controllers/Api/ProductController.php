<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('category', 'attributes')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'attributes' => 'array',
        ]);

        $product = Product::create($request->only(['name', 'category_id']));

        // Process attributes and store them in pivot table
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->input('attributes') as $attribute) {
                foreach ($attribute as $attributeName => $attributeValue) {
                    $attributeModel = Attribute::where('name', '=', $attributeName)->where('value', '=', $attributeValue)->first();
                    if ($attributeModel){
                        $attributes[] = $attributeModel->id;
                    }
                    else{
                        return $this->errorResponse("Invalid attribute information", 500);
                    }
                }
            }
            $product->attributes()->sync($attributes);
        }

        return $product->load('category', 'attributes');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Product::with('category', 'attributes')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'attributes' => 'array',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'category_id']));

        // Process attributes and update the pivot table
        if ($request->has('attributes')) {
            $attributes = [];
            foreach ($request->input('attributes') as $attribute) {
                foreach ($attribute as $attributeName => $attributeValue) {
                    $attributeModel = Attribute::where('name', '=', $attributeName)->where('value', '=', $attributeValue)->first();
                    if ($attributeModel){
                        $attributes[] = $attributeModel->id;
                    }
                    else{
                        return $this->errorResponse("Invalid attribute information", 500);
                    }
                }
            }
            $product->attributes()->sync($attributes);
        }

        return $product->load('category', 'attributes');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->attributes()->detach();
        $product->delete();

        return response()->noContent();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (empty($query)) {
            return response()->json([]);  // Return an empty array if no query is provided
        }
    
        // Use a unique cache key based on the search query
        $cacheKey = 'products_search_' . md5($query);
    
        // Check if the search results are already cached
        $products = Cache::remember($cacheKey, 600, function () use ($query) {
            return Product::with('category', 'attributes')
                ->where('name', 'like', "{$query}%")
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'like', "{$query}%");
                })
                ->orWhereHas('attributes', function ($q) use ($query) {
                    $q->where('value', 'like', "{$query}%");
                })
                ->get();
        });
    
        // Check if products are found
        if ($products->isEmpty()) {
            return response()->json([], 404);  // Return a 404 response if no products are found
        }
    
        return response()->json($products);
    }
}

