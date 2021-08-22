<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'udid',
        'app_id',
        'os',
        'os_version',
        'device_model',
        'client_token'
    ];
}
