<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'status' => $this->status,
            'description' => $this->description,
            'color' => $this->color,
            'year' => $this->year,
            'model' => $this->model ? $this->model->name : 'not selected',
            'type' => $this->type ? $this->type->name : 'not selected',
            'motor' => $this->motor ? $this->motor->name : 'not selected',
        ];
    }
}
