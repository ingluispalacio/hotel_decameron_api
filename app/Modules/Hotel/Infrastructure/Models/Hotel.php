<?php

namespace App\Modules\Hotel\Infrastructure\Models;

use App\Modules\Hotel\Domain\Entities\City;
use App\Modules\Hotel\Domain\Entities\HotelConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Shared\Traits\HasUuid;

class Hotel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    protected $table = 'hotels';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'address',
        'city_id',
        'nit',
        'max_rooms'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function configurations()
    {
        return $this->hasMany(
            HotelConfiguration::class
        );
    }
}