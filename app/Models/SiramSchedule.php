<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiramSchedule extends Model
{

    protected $fillable = [
        "active_status",
        "time",
        "duration",
        "days"
    ];
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
