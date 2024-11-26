<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleResourceResponse extends JsonResponse
{
    public function __construct(?JsonResource $resource) {
        parent::__construct([
            'resource'=> $resource,
        ]);
    }
}