<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'url'             => $this->url,
            'is_medical_pass' => $this->is_medical_pass ?? null,
            'is_written_pass' => $this->is_written_pass ?? null,
            'is_final_pass'   => $this->is_final_pass ?? null,
            'is_viva_pass'    => $this->is_viva_pass ?? null,
            'application' => $this->application ? [
                'post'  => $this->application->post,
                'batch' => $this->application->batch,
                'roll'  => $this->application->roll,
                'name'  => $this->application->name,
            ] : null,
        ];
    }
}
