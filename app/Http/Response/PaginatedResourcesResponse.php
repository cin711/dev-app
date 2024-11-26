<?php

namespace App\Http\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaginatedResourcesResponse extends JsonResponse
{
    public function __construct(?AnonymousResourceCollection $data, int $pageNumber, int $pageSize, int $totalRecords)
    {
        parent::__construct([
            'items'         => $data,
            'page_number'   => $pageNumber,
            'page_size'     => $pageSize,
            'total_pages'   => floor($totalRecords / $pageSize + 1),
            'total_records' => $totalRecords,
        ]);
    }
}