<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDetail;
use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $details = PurchaseDetail::all();

        return response()->json($details);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $detail = PurchaseDetail::create($request->all());

        return response()->json([
            "message" => "Detalle de compra registrado con éxito",
            "detail" => $detail
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = PurchaseDetail::find($id);
        if (!$detail) {
            return response()->json(["message" => "Detalle no encontrado"], 404);
        }

        return response()->json($detail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detail = PurchaseDetail::find($id);
        if (!$detail) {
            return response()->json(["message" => "Detalle no encontrado"], 404);
        }

        $detail->update($request->all());

        return response()->json(["message" => "Detalle de compra actualizado con éxito"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = PurchaseDetail::find($id);
        if (!$detail) {
            return response()->json(["message" => "Detalle no encontrado"], 404);
        }

        $detail->delete();

        return response()->json(["message" => "Detalle de compra eliminado con éxito"]);
    }
}
