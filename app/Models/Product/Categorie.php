<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "name_categorie",
        "imagen_url",
        "state"
    ];
    public function setCreatedAtAttribute($value)
    {
        $this->attributes["created_at"] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes["updated_at"] = Carbon::now('America/Lima');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
