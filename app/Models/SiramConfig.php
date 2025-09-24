<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiramConfig extends Model
{
    /** @use HasFactory<\Database\Factories\SiramConfigFactory> */
    use HasFactory;

    protected $primaryKey = 'siram_id';
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
