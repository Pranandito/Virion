<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiramActivityLog extends Model
{
    protected $fillable = [
        'device_id',
        'mode',
        'duration',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function desc(): string
    {
        return match ($this->mode) {
            'Otomatis' => 'kelembapan tanah rendah',
            'Manual' => 'perubahan mode perangkat',
            'Jadwal' => 'pengaturan jadwal penyiraman',
            default => 'kelembapan tanah rendah',
        };
    }
}
