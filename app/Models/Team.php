<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'description', 'username', 'password'];

    /**
     * The users that belong to the Team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
        ->withTimestamps()
        ->withPivot(['owner']);
    }

    /**
     * Returns the Owners of the teams
     */
    public function owners()
    {
        return $this->belongsToMany(User::class)
        ->withTimestamps()
        ->withPivot(['owner'])
        ->wherePivot('owner', 1);
    }

    /**
     * Returns the regular users that are not team owners
     */
    public function notOwners()
    {
        return $this->belongsToMany(User::class)
        ->withTimestamps()
        ->withPivot(['owner'])
        ->wherePivot('owner', 0);
    }

    /**
     * Get all of the refs for the Team
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refs()
    {
        return $this->hasMany(Ref::class);
    }
}
