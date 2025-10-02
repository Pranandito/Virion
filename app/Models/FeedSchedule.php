<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedSchedule extends Model
{
    //
    protected $fillable = [
        "active_status",
        "time",
        "portion",
        "days"
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function config()
    {
        return $this->hasOneThrough(
            FeedConfig::class,
            Device::class,
            'id',            // foreign key di Device yang dirujuk Schedule.device_id
            'device_id',     // foreign key di Config
            'device_id',     // local key di Schedule
            'id'             // local key di Device);
        );
    }
}
