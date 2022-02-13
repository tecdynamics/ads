<?php

namespace Tec\Ads\Repositories\Caches;

use Tec\Support\Repositories\Caches\CacheAbstractDecorator;
use Tec\Ads\Repositories\Interfaces\AdsInterface;

class AdsCacheDecorator extends CacheAbstractDecorator implements AdsInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
