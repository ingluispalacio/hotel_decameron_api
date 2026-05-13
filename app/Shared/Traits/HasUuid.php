<?php

namespace App\Shared\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


/**
 * @mixin Model
 */
trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        // Usamos self:: para referirnos a la clase que use el trait
        static::creating(function (Model $model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}