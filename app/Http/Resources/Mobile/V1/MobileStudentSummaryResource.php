<?php

namespace App\Http\Resources\Mobile\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileStudentSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_number' => $this->student_number,
            'nisn' => $this->nisn,
            'full_name' => $this->full_name,
            'nickname' => $this->nickname,
            'gender' => $this->gender,
            'program_type' => $this->program_type,
            'class_room' => $this->classRoom ? [
                'id' => $this->classRoom->id,
                'name' => $this->classRoom->name,
                'level' => $this->classRoom->level,
            ] : null,
        ];
    }
}
