<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['name', 'device_uuid'];
}
