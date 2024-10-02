<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\CategorieCollection;
use App\Http\Resources\Product\CategorieResource;
use App\Models\Product\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $categories = Categorie::where("name_categorie", "like", "%" . $search . "%")->orderBy("id", "desc")->paginate(10);

        return response()->json([
            "total" => $categories->total(),
            "categories" => CategorieCollection::make($categories)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = Categorie::where("name_categorie", $request->name_categorie)->first();
        if ($is_exists) {
            return response()->json(["message" => 403]);
        }
        if ($request->hasFile("image")) {
            $path = Storage::putFile("categories", $request->file("image"));
            $request->request->add(["imagen_url" =>  $path]);
        }
        $categorie = Categorie::create($request->all());
        return response()->json(["message" => 200]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = Categorie::findOrFail($id);
        return response()->json([
            "ok" => true,
            "code" => 200,
            "categorie" => CategorieResource::make($categorie)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $is_exists = Categorie::where("id", '<>', $id)->where("name_categorie", $request->name_categorie)->first();
        if ($is_exists) {
            return response()->json(["message" => 403]);
        }
        $categorie = Categorie::findOrFail($id);
        if ($request->hasFile("image")) {
            if ($categorie->imagen_url) {
                Storage::delete($categorie->imagen_url);
            }
            $path = Storage::putFile("categories", $request->file("image"));
            $request->request->add(["imagen_url" =>  $path]);
        }
        $categorie->update($request->all());
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
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();

        // todo: validar que la categoria no este no en ningun producto
        return response()->json([
            "ok" => true,
            "message" => "categoria elimanada correctamente",
            "code" => 200,
        ]);
    }
}
