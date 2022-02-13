<?php

namespace Tec\Ads\Repositories\Interfaces;

use Tec\Support\Repositories\Interfaces\RepositoryInterface;

interface AdsInterface extends RepositoryInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getAll();
}
