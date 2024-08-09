<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $body = $request->json()->all();

        $categoryResponse = Category::create($body);

        return $this->successResponse($categoryResponse, "Category Information Inserted");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return $this->successResponse($category, "Category fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->json()->all());

        return $this->successResponse($category, "Category updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return $this->successResponse("Category deleted successfully");
    }
}
