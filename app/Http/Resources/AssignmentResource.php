<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'assigment_id'     =>$this->id,
            'name'            =>$this->name,
            'total_questions' =>$this->total_questions,
            'start_time' =>$this->start_time,
            'end_time' =>$this->end_time,
            'status' =>$this->status,
            'progress' =>$this->progress_format,
            'image' => $this->getFirstMediaUrl('images'),
            'questions' => AssignmentQuestionResource::collection($this->questions),
            'participant' =>$this->user_participation,
            'participation_status' =>$this->user_participation ? true: false
        ];
    }
}
