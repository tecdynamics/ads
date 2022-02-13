<?php

namespace Tec\Ads\Repositories\Eloquent;

use Tec\Base\Enums\BaseStatusEnum;
use Tec\Support\Repositories\Eloquent\RepositoriesAbstract;
use Tec\Ads\Repositories\Interfaces\AdsInterface;

class AdsRepository extends RepositoriesAbstract implements AdsInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->with(['metadata']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
