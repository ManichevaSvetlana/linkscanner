<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relationship: get all roles connected to the user
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * Relationship: get all user-roles entries connected to the user
     *
     * @return HasMany
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Relationship: get all user-roles entries connected to the user
     *
     * @return HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Check if the user has the role by name
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->roles()->whereName(strtolower($role))->exists();
    }

    /**
     * Check if the user is admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') && $this->userRoles()->whereRoleId(Role::whereName('admin')->first()->id)->first()->active;
    }

    /**
     * Get the admin user
     *
     * @param mixed $query
     * @return User
     */
    public function scopeAdmin($query)
    {
        return $query->whereEmail( env('ADMIN_USER') )->first();
    }

    /**
     * Delete the user with his links
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete()
    {
        $this->links()->delete();
        return parent::delete();
    }
}
