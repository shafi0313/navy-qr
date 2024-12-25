<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationShowResource extends JsonResource
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
            'candidate_designation' => $this->candidate_designation,
            'exam_date' => $this->exam_date,
            'serial_no' => $this->serial_no,
            'eligible_district' => $this->eligible_district,
            'name' => $this->name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'photo' => $this->photo,
            'is_medical_pass' => $this->is_medical_pass ?? null,
            'is_final_pass' => $this->is_final_pass ?? null,
        ];
    }
}
