<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "cliente_id",
        "usuario_id",
        "tipo_comprobante",
        "serie_comprobante",
        "numero_comprobante",
        "fecha_venta",
        "subtotal",
        "igv",
        "total",
        "estado",
        "notas" ,
    ];

    public function setCreatedAtAttribute($value)
    {
        $this->attributes["created_at"] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes["updated_at"] = Carbon::now('America/Lima');
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
