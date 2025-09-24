<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevicesLog extends Model
{
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'device_id',
        'activity',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
