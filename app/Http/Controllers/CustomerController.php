<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $customers = Customer::where("nombre", "like", "%" . $search . "%")
            ->orWhere("numero_documento", "like", "%" . $search . "%")
            ->orderBy("id", "desc")
            ->paginate(10);

        return response()->json([
            "total" => $customers->total(),
            "clientes" => $customers->items()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $is_exists = Customer::where("numero_documento", $request->numero_documento)
            ->orWhere("email", $request->email)
            ->first();

        if ($is_exists) {
            return response()->json([
                "ok" => false,
                "message" => "El número de documento o email ya existe",
                "code" => 403
            ]);
        }

        try {
            $customer = Customer::create([
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'estado' => $request->estado ?? 1
            ]);

            return response()->json([
                "ok" => true,
                "message" => "Cliente registrado correctamente",
                "code" => 200,
                "cliente" => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "message" => "Error al registrar el cliente",
                "error" => $e->getMessage(),
                "code" => 500
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                "ok" => true,
                "code" => 200,
                "cliente" => $customer
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "message" => "Cliente no encontrado",
                "code" => 404
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Validar si existe otro cliente con el mismo documento o email
            $is_exists = Customer::where("id", "<>", $id)
                ->where(function($query) use ($request) {
                    $query->where("numero_documento", $request->numero_documento)
                        ->orWhere("email", $request->email);
                })
                ->first();

            if ($is_exists) {
                return response()->json([
                    "ok" => false,
                    "message" => "El número de documento o email ya existe en otro cliente",
                    "code" => 403
                ]);
            }

            $customer->update([
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'estado' => $request->estado
            ]);

            return response()->json([
                "ok" => true,
                "message" => "Cliente actualizado correctamente",
                "code" => 200,
                "cliente" => $customer
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "message" => "Error al actualizar el cliente",
                "error" => $e->getMessage(),
                "code" => 500
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                "ok" => true,
                "message" => "Cliente eliminado correctamente",
                "code" => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "message" => "Error al eliminar el cliente",
                "error" => $e->getMessage(),
                "code" => 500
            ]);
        }
    }

}
