<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):JsonResponse
    {

        $search = $request->search;
        $suppliers = Supplier::where("nombre", "like", "%" . $search . "%")
            ->orderBy("nombre", "asc")
            ->paginate(10);

        return response()->json([
            "total" => $suppliers->total(),
            "suppliers" => $suppliers->items()
        ]);
    }


    public function combo(): JsonResponse
    {
        try {
            $suppliers = Supplier::select('id', 'nombre', 'email', 'telefono', 'direccion')
                ->where('estado', 1)
                ->orderBy('nombre')
                ->get();

            return response()->json([
                "ok" => true,
                "message" => "Proveedores obtenidos exitosamente",
                "suppliers" => $suppliers
            ]);
        } catch (\Exception $e) {
            Log::error('Error al listar proveedores: ' . $e->getMessage());

            return response()->json([
                "ok" => false,
                "message" => "Error al obtener proveedores",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {
        $is_exists = Supplier::where("nombre", $request->nombre)->first();
        if ($is_exists) {
            return response()->json(["message" => 403]);
        }

        $supplier = Supplier::create($request->all());

        return response()->json([
            "ok" => true,
            "message" => 200,
            "supplier" => $supplier
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json([
            $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id):JsonResponse
    {
        $is_exists = Supplier::where("id", '<>', $id)
        ->where("nombre", $request->nombre)
        ->first();

        if ($is_exists) {
            return response()->json(["message" => 403]);
        }

        $supplier = Supplier::findOrFail($id);


        $supplier->update($request->all());

        return response()->json([
            "ok" => true,
            "message" => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id):JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json([
            "ok" => true,
            "message" => "Proveedor eliminado correctamente",
            "code" => 200
        ]);
    }
}
