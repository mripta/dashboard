<?php

namespace App\Models;

use App\Models\Team;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'bio',
        'email',
        'password',
        'admin',
        'image',
        'last_ip_address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The teams that the User belong to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('owner');
    }

    /**
     * Returns if the user is admin
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * Returns if the user is owner of the provided Team
     * 
     * @return boolean
     */
    public function isOwner(Team $team)
    {
        foreach($team->owners as $owner)
        {
            if($this->id == $owner->id)
                return true;
        }
        return false;
    }

    /**
     * Returns if the user belongs to the provided Team
     * 
     * @return boolean
     */
    public function isMember(Team $team)
    {
        foreach($team->users as $user)
        {
            if($this->id == $user->id)
                return true;
        }
        return false;
    }
}
