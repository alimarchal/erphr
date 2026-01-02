<?php

namespace App\Models;

use App\Traits\UserTracking;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use UserTracking;
    use SoftDeletes;
    use HasUuids;
    protected $fillable = [
        'name',
        'short_name',
    ];
}
