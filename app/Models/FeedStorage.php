<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedStorage extends Model
{
    //

    protected $fillable = [
        "storage",
        "online_duration"
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
