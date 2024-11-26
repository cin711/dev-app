<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;


/**
 * @extends Repository<int, \App\Models\Department>
 */
class DepartmentRepository extends Repository
{

    public function create(Department $department): Department {
        $data = $this->databaseManager->select(
            "CALL insert_department(:name, :parent_id, :flags, :created_at)",
            [
                'name' => $department->name,
                'parent_id' => $department->parent_id,
                'flags' => $department->flags,
                'created_at' => $department->created_at,
            ]
        );

        $department->id = $data[0]?->ID;
        return $department;
    }

    public function update(Department $department): Department {
        $this->databaseManager->statement(
            'CALL update_department(:id, :name, :parent_id, :flags, :updated_at)',
            [
                'id' => $department->id,
                'name' => $department->name,
                'parent_id' => $department->parent_id,
                'flags' => $department->flags,
                'updated_at' => $department->updated_at,
            ]
        );

        if ($department->parent_id === null)
        {
            $department->setRelation('parent', null);
        } else {
            $department->setRelation('parent', $this->findById($department->parent_id));
        }

        return $department;
    }

    public function setHierarchyFlag(Department $department, int $flag) {
        $this->databaseManager->statement(
            'CALL set_department_hierarchy_flag(:id, :flag)',
            [
                'id' => $department->id,
                'flag' => $flag
            ]
        );
    }

    public function unsetHierarchyFlag(Department $department, int $flag) {
        $this->databaseManager->statement(
            'CALL unset_department_hierarchy_flag(:id, :flag)',
            [
                'id' => $department->id,
                'flag' => $flag
            ]
        );
    }


    public function findById(int $id): ?Department {
        $data = $this->databaseManager->select(
            "CALL get_department_by_id(:id)",
             ['id' => $id]
        );

        return Department::hydrate($data)->first();
    }

    public function findByIdWithParent(int $id): ?Department {
        $department = $this->findById($id);
        if ($department === null) {
            return null;
        }

        if (! $department->parent_id) {
            return $department;
        }

        $department->setRelation('parent', $this->findById($department->parent_id));
        return $department;
    }

    /**
     * @param int $page
     * @param int $pageSize
     * @return \Illuminate\Database\Eloquent\Collection<int, Department>
     */
    public function findAll(int $page, int $pageSize = 50): Collection {
        $data = $this->databaseManager->select(
            "CALL get_departments(:limit, :offset)",
             [
                'limit' => $pageSize,
                'offset' => ($page - 1) * $pageSize,
            ]
        );

        return Department::hydrate($data);
    }

    public function countAll(): int {
        return $this->databaseManager->select(
            'CALL count_departments()'
        )[0]?->COUNT ?? 0;
    }

    public function findAllChildrenIdsReccursive(string $name): array {
        $data = $this->databaseManager->select(
            'CALL get_department_hierarchy(:name)',
            ['name' => $name]
        );

        return empty($data) ? [] : array_column($data, 'id');
    }

    public function findWithHierarchy(string $name): ?Department {
        $data = $this->databaseManager->select(
            'CALL get_department_hierarchy(:name)',
            ['name' => $name]
        );

        if (empty($data)) {
            return null;
        }

        if (sizeof($data) === 1) {
            return Department::hydrate($data)->first();
        }

        return $this->hydrateHierarchy(Department::hydrate($data));
    }

    /**
     * @param Collection<int, Department> $departments
     */
    private function hydrateHierarchy(Collection $departments, ?Department $current = null, array $visited = []): Department {
        $current = $current ?? $departments->first();
        if (! $current->relationLoaded('children')) {
            $current->setRelation('children', new Collection());
        }
        
        if (empty($visited)) {
            foreach ($departments->all() as $department) {
                $visited[$department->id] = false;
            }    
        }

        if ($visited[$current->id]) {
            return $current;
        }

        foreach($departments->all() as $department) {
            if ($department->parent_id == $current->id) {
                $current->children->push($department);
                $department = $this->hydrateHierarchy($departments, $department, $visited);
                continue;
            }
        }

        return $current;
    }

}