<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HumidaConfig extends Model
{
    protected $primaryKey = 'humida_id';
    protected $fillable = [
        'device_id',
        'upper_threshold',
        'lower_threshold',
        'mode',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
