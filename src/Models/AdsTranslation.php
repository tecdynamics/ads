<?php

namespace Tec\Ads\Models;

use Tec\Base\Models\BaseModel;

class AdsTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ads_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'ads_id',
        'name',
        'image',
        'url',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
