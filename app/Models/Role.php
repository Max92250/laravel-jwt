<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Permission;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Role extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Assign a permission to the role.
     *
     * @param Permission $permission
     * @return void
     */
    public function givePermissionTo(Permission $permission): void
    {
        $this->permissions()->syncWithoutDetaching($permission);
    }

    /**
     * Remove a permission from the role.
     *
     * @param Permission $permission
     * @return void
     */
    public function revokePermissionTo(Permission $permission): void
    {
        $this->permissions()->detach($permission);
    }
}
