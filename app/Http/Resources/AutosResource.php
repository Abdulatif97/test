<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AutosResource extends JsonResource
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
            'image' => $this->image ? url('storage/'. $this->image) : null,
            'status' => $this->status,
            'description' => $this->description,
            'color' => $this->color,
            'year' => $this->year,
            'model' => new AutoTypesResource($this->model),
            'type' => new AutoTypesResource($this->type),
            'motor' => new AutoMotorsResource($this->motor),
        ];
    }
}
