<?php

namespace App\Http\Controllers;

use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $saleDetails = SaleDetail::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            "total" => $saleDetails->total(),
            "sale_details" => $saleDetails->items()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            foreach ($request->productos as $producto) {
                SaleDetail::create([
                    'venta_id' => $request->venta_id,
                    'producto_id' => $producto['producto_id'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'subtotal' => $producto['cantidad'] * $producto['precio_unitario'],
                ]);
            }

            return response()->json(["message" => "Detalles de venta registrados con éxito"]);

        } catch (\Exception $e) {
            return response()->json(["message" => "Error al registrar los detalles de venta"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $saleDetail = SaleDetail::findOrFail($id);

        return response()->json([
            "ok" => true,
            "code" => 200,
            "sale_detail" => $saleDetail
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saleDetail = SaleDetail::findOrFail($id);
        $saleDetail->delete();

        return response()->json([
            "ok" => true,
            "message" => "Detalle de venta eliminado correctamente",
            "code" => 200
        ]);
    }
}
