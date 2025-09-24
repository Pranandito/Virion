<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AquaSensor extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'turbidity',
        'pH',
        'oxygen',
        'batt_capacity',
        'batt_voltage',
        'online_duration',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
