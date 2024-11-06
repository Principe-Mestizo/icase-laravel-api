<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovements;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $movements = InventoryMovements::with('product:id,name')
        ->search($request)
        ->orderBy('fecha_movimiento', 'asc')
        ->paginate(10);

          return response()->json([
              'total' => $movements->total(),
              'movements' => $movements->items(),
              'current_page' => $movements->currentPage(),
              'last_page' => $movements->lastPage(),
          ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $inventory_movement = InventoryMovements::findOrFail($id);
        $inventory_movement->delete();

        // todo: validar que la categoria no este no en ningun producto
        return response()->json([
            "ok" => true,
            "message" => "Inventario  elimanado correctamente",
            "code" => 200,
        ]);
    }
}
