<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalData extends Model
{
    protected $table = 'external_data';

    protected $casts = [
        'data' => 'array',
        'last_synced_at' => 'datetime',
    ];

    protected $fillable = [
        'source', 'type', 'data', 'last_synced_at', 'status', 'error_message',
    ];
}
