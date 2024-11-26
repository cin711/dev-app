<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @property \App\Models\Department $resource
 */
class DepartmentResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'is_active' => $this->resource->is_active,
            'is_approved' => $this->resource->is_approved,
            'is_deleted' => $this->resource->is_deleted,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'parent_id' => $this->resource->parent_id,
            'parent' => $this->resource->relationLoaded('parent') ? new DepartmentResource($this->resource->parent) : null,
            'children' => $this->resource->relationLoaded('children') ? DepartmentResource::collection($this->resource->children) : [],
        ];
    }
}