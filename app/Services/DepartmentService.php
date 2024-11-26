<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class DepartmentService
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository
    ) {
    }

    public function store(Department $department) {
        $department->created_at = Carbon::now();
        $department->flags = 0;
        return $this->departmentRepository->create($department);
    }    

    public function update(Department $department) {
        if (
            $department->parent_id !== null &&
            in_array($department->parent_id, $this->departmentRepository->findAllChildrenIdsReccursive($department->name))
        ) {
            throw ValidationException::withMessages(['Invalid parent! (is child of department)']);
        }

        if ($department->parent->is_deleted) {
            throw ValidationException::withMessages(['Invalid parent! (is deleted)']);
        }

        $department->updated_at = Carbon::now();
        return $this->departmentRepository->update($department);
    }

    public function delete(Department $department) {
        $this->departmentRepository->setHierarchyFlag(
            $department,
            Department::IS_DELETED_FLAG_MASK
        );
    }

    public function approve(Department $department) {
        $department->is_approved = true;

        return $this->departmentRepository->update($department);
    }

    public function activate(Department $department) {
        if (! $department->is_approved) {
            throw ValidationException::withMessages(['Department must be approved!']);
        }

        $department->is_active = true;

        return $this->departmentRepository->update($department);
    }

    public function deactivate(Department $department) {
        $department->is_active = false;
        $department = $this->departmentRepository->update($department);

        $this->departmentRepository->unsetHierarchyFlag($department, Department::IS_ACTIVE_FLAG_MASK);
        return $department;
    }


    public function getPaginated(int $page, int $pageSize): Collection
    {
        return $this->departmentRepository->findAll($page, $pageSize);
    }

    public function countAll(): int
    {
        return $this->departmentRepository->countAll();
    }

    public function getWithParent(int $id): ?Department
    {
        $department = $this->departmentRepository->findByIdWithParent($id);
        if (! $department) {
            $exception = new ModelNotFoundException();
            $exception->setModel(Department::class, [$id]);
            throw $exception;
        }

        return $department;
    }

    public function getOrFail(int $id): Department|null {
        $department = $this->departmentRepository->findById($id);
        if (! $department) {
            $exception = new ModelNotFoundException();
            $exception->setModel(Department::class, [$id]);
            throw $exception;
        }

        return $department;
    }

    public function getHierarchy(string $name): Department {
        $department = $this->departmentRepository->findWithHierarchy($name);
        if (! $department) {
            $exception = new ModelNotFoundException();
            $exception->setModel(Department::class);
            throw $exception;
        }

        return $department;
    }
}