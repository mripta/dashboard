<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ref extends Model
{
    protected $fillable = ['ref', 'name'];

    /**
     * Get the team that owns the Ref
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get all of the params for the Ref
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function params()
    {
        return $this->hasMany(Param::class);
    }
}
