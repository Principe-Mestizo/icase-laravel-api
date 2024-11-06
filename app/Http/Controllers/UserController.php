<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
         // Obtener usuarios paginados, 12 por página
         $users = User::paginate(12);

         return response()->json([
             'total' => $users->total(),
             'users' => $users->items(),
             'current_page' => $users->currentPage(),
             'last_page' => $users->lastPage(),
         ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
        ]);

        return response()->json(["message" => "Usuario creado con éxito", "user" => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $req, string $id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "Usuario no encontrado"], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "Usuario no encontrado"], 404);
        }

        // Puedes optar por actualizar solo ciertos campos
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $user->update(array_filter($request->only(['name', 'email', 'password'])));

        // Hash the password if it's being updated
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json(["message" => "Usuario actualizado con éxito", "user" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "Usuario no encontrado"], 404);
        }

        $user->delete();
        return response()->json(["message" => "Usuario eliminado con éxito"]);
    }
}
