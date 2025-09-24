<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumidaSensor extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'online_duration',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
