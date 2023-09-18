<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Carbon\Carbon;

class Data extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'publish';
    protected $primaryKey = '_id';

    protected $dates = [
        'date' => 'date:d/m/Y'
    ];

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

    public function getDate($value)
    {
        // Assuming $value is '14/09/2023', convert it to Carbon instance
        Carbon::setLocale('pt');
        return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}