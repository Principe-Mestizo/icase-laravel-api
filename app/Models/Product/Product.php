<?php

namespace App\Models\Product;

use App\Models\InventoryMovements;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
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


    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovements::class);
    }
}
