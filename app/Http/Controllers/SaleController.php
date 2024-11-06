<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sales = Sale::paginate(10);

        return response()->json([
            "total" => $sales->total(),
            "sales" => $sales->items()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Iniciar transacción
        DB::beginTransaction();
        try {
            // Crear la venta general en la tabla `sales`
            $sale = Sale::create([
                'cliente_id' => $request->cliente_id,
                'usuario_id' => $request->usuario_id,
                'tipo_comprobante' => $request->tipo_comprobante,
                'subtotal' => $request->subtotal,
                'igv' => $request->igv,
                'total' => $request->total,
                'metodo_pago' => $request->metodo_pago,
                'estado' => 1,
            ]);

            // Guardar la venta en la base de datos
            DB::commit();

            return response()->json(["message" => "Venta registrada con éxito", "sale" => $sale]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => "Error al registrar la venta"], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $sale = Sale::findOrFail($id); // Incluye detalles si los tienes relacionados
        return response()->json(["sale" => $sale]);
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
          $sale = Sale::findOrFail($id);
        $sale->delete(); // Realiza la eliminación
        return response()->json(["message" => "Venta eliminada con éxito"]);
    }
}
