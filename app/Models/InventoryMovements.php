<?php

namespace App\Models;

use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMovements extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "producto_id",
        "tipo_movimiento",
        "cantidad" ,
        "referencia_id" ,
        "tipo_referencia",
        "fecha_movimiento" ,
        "notas",
    ];

    public function setCreatedAtAttribute($value)
    {
        $this->attributes["created_at"] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes["updated_at"] = Carbon::now('America/Lima');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }


    public function scopeSearch($query, $request)
    {
        // Búsqueda por tipo de movimiento
        if ($request->has('tipo_movimiento')) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        // Búsqueda por tipo de referencia
        if ($request->has('tipo_referencia')) {
            $query->where('tipo_referencia', $request->tipo_referencia);
        }

        // Búsqueda por rango de fechas
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fecha_movimiento', [
                $request->fecha_inicio . ' 00:00:00',
                $request->fecha_fin . ' 23:59:59'
            ]);
        }

        // Búsqueda por producto (usando la relación)
        if ($request->has('producto')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->producto . '%');
            });
        }

        return $query;
    }
}
