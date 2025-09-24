<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiramSensor extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'online duration'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
