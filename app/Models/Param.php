<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $fillable = ['param', 'name'];

    /**
     * Get the ref that owns the Param
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ref()
    {
        return $this->belongsTo(Ref::class);
    }
}
