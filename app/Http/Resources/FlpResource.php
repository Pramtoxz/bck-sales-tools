<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlpResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_flp' => $this->id_flp,
            'nama' => $this->nama,
            'is_active' => $this->is_active,
            'last_login' => $this->last_login?->format('Y-m-d H:i:s'),
        ];
    }
}
