<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithShortId
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'uuid';
    }

    public static function booted()
    {
        static::creating(function (Model $model) {
            // Set attribute for new model's primary key (ID) to an uuid.
            if (!$model->getKey())
                $model->setAttribute($model->getKeyName(), bin2hex(random_bytes(4)));
        });
    }
}
