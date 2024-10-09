<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'                    => $this->id,
            'serial_no'             => $this->serial_no,
            'candidate_designation' => $this->candidate_designation,
            'eligible_district'     => $this->eligible_district,
            'name'                  => $this->name,
            'is_medical_pass'       => $this->is_medical_pass ?? null,
            'is_final_pass'         => $this->is_final_pass ?? null,
        ];

        if (in_array(user()->role_id, [1, 2, 3, 4, 5])) {
            $data['examMark'] = [
                'bangla'            => $this->examMark->bangla,
                'english'           => $this->examMark->english,
                'math'              => $this->examMark->math,
                'science'           => $this->examMark->science,
                'general_knowledge' => $this->examMark->general_knowledge,
                'viva'              => $this->examMark->viva,
            ];
        }

        return $data;
        // return [
        //     'id'                    => $this->id,
        //     'serial_no'             => $this->serial_no,
        //     'candidate_designation' => $this->candidate_designation,
        //     'eligible_district'     => $this->eligible_district,
        //     'name'                  => $this->name,
        //     'is_medical_pass'       => $this->is_medical_pass ?? null,
        //     'is_final_pass'         => $this->is_final_pass ?? null,

        //     'examMark'  => $this->examMark ? [
        //         'bangla'            => $this->examMark->bangla,
        //         'english'           => $this->examMark->english,
        //         'math'              => $this->examMark->math,
        //         'science'           => $this->examMark->science,
        //         'general_knowledge' => $this->examMark->general_knowledge,
        //         'viva'              => $this->examMark->viva,
        //     ] : null,
        // ];
    }
}
