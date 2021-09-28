<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['name', 'team_id', 'ref_id', 'param_id', 'min', 'max', 'enabled'];

    /**
     * The Team that the Alert belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * The Ref that the Alert belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ref()
    {
        return $this->belongsTo(Ref::class);
    }

    /**
     * The Param that the Alert belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function param()
    {
        return $this->belongsTo(Param::class);
    }
}
