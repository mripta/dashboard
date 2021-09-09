<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Data extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'publish';
    protected $primaryKey = '_id';

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '_id', 'cmd', 'payload', 'timestamp', 'teamid'
    ];

    // returns the object id
    public static function getID($id)
    {
        return new \MongoDB\BSON\ObjectID($id);
    }

    //returns the object timestamp from the mongodb ID
    public function getTime()
    {
        $time = new \MongoDB\BSON\ObjectID($this->_id);
        return $time->getTimestamp();
    }
}