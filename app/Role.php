<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','guard_name', 'description'];

    /**
     * Get the permissions for the role.
     */
    public function permission(){
        return $this->belongsToMany(Permission::class,'role_has_permissions');
    }
}
