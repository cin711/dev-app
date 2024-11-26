<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentCreateRequest;
use App\Http\Requests\DepartmentIndexRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Resources\DepartmentListResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Response\PaginatedResourcesResponse;
use App\Http\Response\SingleResourceResponse;
use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly DepartmentService $departmentService
    ) {
    }

    public function index(DepartmentIndexRequest $request) {
        return new PaginatedResourcesResponse(
            DepartmentListResource::collection(
                $this->departmentService->getPaginated(
                    $request->get('page', 1),
                    $request->get('page_size', 50)
                )
            ),
            $request->get('page', 1),
            $request->get('page_size', 50),
            $this->departmentService->countAll(),
        );
    }

    public function get(Request $request, int $id) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->getWithParent($id)
            )
        );
    }

    public function hierarchy(Request $request, string $name) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->getHierarchy($name)
            )
        );
    }

    public function create(DepartmentCreateRequest $request) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->store(
                    new Department([
                        'name' => $request->get('name'),
                        'parent_id' => $request->get('parent_id')
                    ])
                )
            )
        );
    }

    public function update(DepartmentUpdateRequest $request, int $id) {
        $department = $this->departmentService->getWithParent($id);
        $department->fill([
                'name' => $request->get('name'),
                'parent_id' => $request->get('parent_id')
        ]);

        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->update($department)
            )
        );
    }

    public function activate(Request $request, int $id) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->activate(
                    $this->departmentService->getOrFail($id)
                )
            )
        );
    }

    public function deactivate(Request $request, int $id) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->deactivate(
                    $this->departmentService->getOrFail($id)
                )
            )
        );
    }

    public function approve(Request $request, int $id) {
        return new SingleResourceResponse(
            new DepartmentResource(
                $this->departmentService->approve(
                    $this->departmentService->getOrFail($id)
                )
            )
        );
    }

    public function delete(Request $request, int $id) {
        $this->departmentService->delete($this->departmentService->getOrFail($id));
        return new SingleResourceResponse(
            null
        );
    }
}