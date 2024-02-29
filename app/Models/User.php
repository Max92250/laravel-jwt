<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email', 'password', 'customer_id', 'type', 'username', 'created_by', 'updated_by',
    ];

    public function isAdmin()
    {
        return $this->type === 'admin';
    }
    public function isUser()
    {
        return $this->type === 'user';
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hasRole($role)
    {
        return $this->type === $role;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function Role(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching($role);
    }

    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role);
    }
    
   /* public function can($permissions, $arguments = [])
    {
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        foreach ($this->roles as $role) {
            foreach ($permissions as $permission) {
                if ($role->permissions()->where('name', $permission)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }
    */
    public function hasPermission(string $permissionName): bool
    {
        // Retrieve all roles associated with the user
        $roles = $this->roles()->with('permissions')->get();

        // Flatten the permissions and check if the specified permission exists
        return $roles->pluck('permissions')->flatten()->pluck('name')->contains($permissionName);
    }
    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public $timestamps = false;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
