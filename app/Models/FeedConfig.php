<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedConfig extends Model
{
    //
    protected $fillable = [
        "pelet_size",
        "lasr_refill"
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
