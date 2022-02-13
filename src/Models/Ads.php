<?php

namespace Tec\Ads\Models;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Base\Models\BaseModel;
use Tec\Base\Traits\EnumCastable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Ads extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ads';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'status',
        'expired_at',
        'location',
        'image',
        'url',
        'clicked',
        'order',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'expired_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereDate('expired_at', '>=', now()->toDateString());
        });
    }

    /**
     * @return string|null
     */
    public function getExpiredAtAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value)->format('m/d/Y');
    }
}
