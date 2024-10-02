<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string ,mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name_categorie" => $this->name_categorie,
            "imagen_url" => $this->imagen_url ? env("APP_URL") . "/storage" . "/" . $this->imagen_url : null,
            "state" => $this->state,
            "created_at" => $this->created_at->format("Y-m-d H:i:s")
        ];
    }
}
