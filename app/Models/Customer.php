<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "tipo_documento" ,
        "numero_documento" ,
        "nombre",
        "telefono" ,
        "email" ,
        "direccion" ,
        "fecha_nacimiento",
        "estado",
    ];


    public function setCreatedAtAttribute($value)
    {
        $this->attributes["created_at"] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes["updated_at"] = Carbon::now('America/Lima');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

}
