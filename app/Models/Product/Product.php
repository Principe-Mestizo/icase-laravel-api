<?php

namespace App\Models\Product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "category_id",
        "name",
        "description",
        "price",
        "stock",
        "imagen_url",
        "state"
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'state' => 'integer',
    ];

    public function setCreatedAtAttribute($value)
    {
        $this->attributes["created_at"] = Carbon::now('America/Lima');
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes["updated_at"] = Carbon::now('America/Lima');
    }

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }
}
