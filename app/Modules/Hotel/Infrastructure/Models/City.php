<?php

namespace App\Modules\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Shared\Traits\HasUuid;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    protected $table = 'cities';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name'
    ];
}