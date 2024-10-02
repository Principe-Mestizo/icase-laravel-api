<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $products = Product::where("name", "like", "%" . $search . "%")->orderBy("id", "desc")->paginate(12);

        return response()->json([
            "total" => $products->total(),
            "productos" => ProductCollection::make($products)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = Product::where("name", $request->name)->first();
        if ($is_exists) {
            return response()->json(["message" => 403]);
        }
        if ($request->hasFile("image")) {
            $path = Storage::putFile("productos", $request->file("image"));
            $request->request->add(["imagen_url" =>  $path]);
        }
        $categorie = Product::create($request->all());
        return response()->json([
            "ok" => true,
            "message" => 200
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Product::findOrFail($id);
        return response()->json([
            "ok" => true,
            "code" => 200,
            "producto" => ProductResource::make($producto)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_exists = Product::where("id", '<>', $id)->where("name", $request->name_categorie)->first();
        if ($is_exists) {
            return response()->json(["message" => 403]);
        }
        $product = Product::findOrFail($id);
        if ($request->hasFile("image")) {
            if ($product->imagen_url) {
                Storage::delete($product->imagen_url);
            }
            $path = Storage::putFile("productos", $request->file("image"));
            $request->request->add(["imagen_url" =>  $path]);
        }
        $product->update($request->all());
        return response()->json([
            "ok" => true,
            "message" => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // todo: validar que la categoria no este no en ningun producto
        return response()->json([
            "ok" => true,
            "message" => "categoria elimanada correctamente",
            "code" => 200,
        ]);
    }
}
