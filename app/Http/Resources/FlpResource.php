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
            'no_id' => $this->no_id,
            'nama' => $this->nama,
            'kd_dlr' => $this->kd_dlr,
            'jabatan' => $this->jabatan,
            'team' => $this->team,
            'foto' => $this->foto ? url($this->foto) : null,
        ];
    }
}
