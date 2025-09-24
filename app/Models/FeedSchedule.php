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
}
