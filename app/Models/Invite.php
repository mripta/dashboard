<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'email', 'token', 'registered_at', 'created_at'
    ];

    public function generateInviteToken() {
        $this->token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }

    public function getLink() {
        return urldecode(route('register') . '?token=' . $this->token);
    }
}
