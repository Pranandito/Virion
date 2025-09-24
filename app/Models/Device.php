<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Device extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'serial_number',
        'firmware_version',
        'status',
        'virdi_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function devices_logs()
    {
        return $this->hasMany(DevicesLog::class);
    }

    public function humida_config()
    {
        return $this->hasOne(HumidaConfig::class);
    }

    public function siram_config()
    {
        return $this->hasOne(SiramConfig::class);
    }

    public function humida_sensors()
    {
        return $this->hasMany(HumidaSensor::class);
    }

    public function aqua_sensors()
    {
        return $this->hasMany(AquaSensor::class);
    }

    public function siram_sensors()
    {
        return $this->hasMany(SiramSensor::class);
    }

    public function feed_config()
    {
        return $this->hasOne(FeedConfig::class);
    }

    public function feed_schedule()
    {
        return $this->hasMany(FeedSchedule::class);
    }

    public function feed_storage()
    {
        return $this->hasMany(FeedStorage::class);
    }
}
