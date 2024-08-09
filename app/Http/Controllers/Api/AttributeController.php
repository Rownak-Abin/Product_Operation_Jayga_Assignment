<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use ApiResponderTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Attribute::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $body = $request->json()->all();

        $attributeResponse = Attribute::create($body);

        return $this->successResponse($attributeResponse, "Attribute Information Inserted");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $attribute = Attribute::findOrFail($id);

        return $this->successResponse($attribute, "Attribute fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $attribute = Attribute::findOrFail($id);
        $attribute->update($request->json()->all());

        return $this->successResponse($attribute, "Attribute updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Attribute::findOrFail($id)->delete();

        return $this->successResponse("Attribute deleted successfully");
    }
}
