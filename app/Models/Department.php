<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Summary of Department
 * @property int $id
 * @property string $name
 * @property int $flags
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int|null $parent_id
 * -- relations --
 * @property Department|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Department> $children
 * -- magic --
 * @property bool $is_active
 * @property bool $is_deleted
 * @property bool $is_approved
 */
class Department extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'flags' => 'integer',
    ];

    public const IS_ACTIVE_FLAG_MASK = 1; // 001
    public const IS_DELETED_FLAG_MASK = 2; // 010
    public const IS_APPROVED_FLAG_MASK = 4; // 100

    public function parent()
    {
        return $this->belongsTo(Department::class);
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function getIsActiveAttribute(): bool {
        return ($this->flags & self::IS_ACTIVE_FLAG_MASK) === self::IS_ACTIVE_FLAG_MASK;
    }

    public function setIsActiveAttribute(bool $active): void {
        $this->flags = $active ? 
            $this->flags | self::IS_ACTIVE_FLAG_MASK :
            $this->flags & ~self::IS_ACTIVE_FLAG_MASK;
    }

    public function getIsDeletedAttribute(): bool {
        return ($this->flags & self::IS_DELETED_FLAG_MASK) === self::IS_DELETED_FLAG_MASK;
    }

    public function setIsDeletedAttribute(bool $deleted): void {
        $this->flags = $deleted ?
            $this->flags | self::IS_DELETED_FLAG_MASK :
            $this->flags & ~self::IS_DELETED_FLAG_MASK;
    }

    public function getIsApprovedAttribute(): bool {
        return ($this->flags & self::IS_APPROVED_FLAG_MASK) === self::IS_APPROVED_FLAG_MASK;
    }

    public function setIsApprovedAttribute(bool $approved): void {
        $this->flags = $approved ?
            $this->flags | self::IS_APPROVED_FLAG_MASK :
            $this->flags & ~self::IS_APPROVED_FLAG_MASK;
    }

}
