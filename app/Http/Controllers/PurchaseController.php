<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        $purchases = Purchase::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            "total" => $purchases->total(),
            "purchases" => $purchases->items()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $total = $request->total;
            $tasaIGV = 0.18;

            $subtotal = $total / (1 + $tasaIGV);
            $igv = $total - $subtotal;

            $purchase = Purchase::create([
                'proveedor_id' => $request->proveedor_id,
                'numero_factura' => $request->numero_factura,
                'subtotal' => round($subtotal, 2),
                'igv' => round($igv, 2),
                'total' => $total,
                'estado' => $request->estado,
                'notas' => $request->notas,
            ]);

            DB::commit();

            return response()->json([
                "message" => "Compra registrada con éxito",
                "purchase" => $purchase
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Error al registrar la compra",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $purchase = Purchase::create([
    //             'proveedor_id' => $request->proveedor_id,
    //             'numero_factura' => $request->numero_factura,
    //             'subtotal' => $request->subtotal,
    //             'igv' => $request->igv,
    //             'total' => $request->total,
    //             'estado' => $request->estado,
    //             'notas' => $request->notas,
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             "message" => "Compra registrada con éxito",
    //             "purchase" => $purchase
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             "message" => "Error al registrar la compra",
    //             "error" => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(["message" => "Compra no encontrada"], 404);
        }

        return response()->json($purchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(["message" => "Compra no encontrada"], 404);
        }

        $purchase->update($request->all());

        return response()->json(["message" => "Compra actualizada con éxito"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(["message" => "Compra no encontrada"], 404);
        }

        $purchase->delete();

        return response()->json(["message" => "Compra eliminada con éxito"]);
    }
}
