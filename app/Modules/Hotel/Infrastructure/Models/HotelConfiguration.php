<?php

namespace App\Modules\Hotel\Infrastructure\Models;

use App\Modules\Hotel\Domain\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Shared\Traits\HasUuid;

class HotelConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    protected $table = 'hotel_configurations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'accommodation_id',
        'quantity'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}