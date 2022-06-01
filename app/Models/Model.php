<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\WithUuid;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    use Cachable, HasFactory, SoftDeletes, WithUuid;
    use LogsActivity;

    protected static $recordEvents = ['created', 'updated', 'deleted'];
    protected static $logName = 'Eloquent';

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
