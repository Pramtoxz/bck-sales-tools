<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppsheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'no_hp' => $this->no_hp,
            'kode_dealer' => $this->kode_dealer,
            'module' => $this->module,
            'jenis_msg' => $this->jenis_msg,
            'message' => $this->message,
            'is_proses' => $this->is_proses ? true : false,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}