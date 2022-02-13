<?php

namespace Tec\Ads\Http\Controllers;

use Tec\Ads\Forms\AdsForm;
use Tec\Ads\Http\Requests\AdsRequest;
use Tec\Ads\Repositories\Interfaces\AdsInterface;
use Tec\Ads\Tables\AdsTable;
use Tec\Base\Events\BeforeEditContentEvent;
use Tec\Base\Events\CreatedContentEvent;
use Tec\Base\Events\DeletedContentEvent;
use Tec\Base\Events\UpdatedContentEvent;
use Tec\Base\Forms\FormBuilder;
use Tec\Base\Http\Controllers\BaseController;
use Tec\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class AdsController extends BaseController
{
    /**
     * @var AdsInterface
     */
    protected $adsRepository;

    /**
     * @param AdsInterface $adsRepository
     */
    public function __construct(AdsInterface $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    /**
     * @param AdsTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(AdsTable $table)
    {
        page_title()->setTitle(trans('plugins/ads::ads.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ads::ads.create'));

        return $formBuilder->create(AdsForm::class)->renderForm();
    }

    /**
     * @param AdsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(AdsRequest $request, BaseHttpResponse $response)
    {
        $ads = $this->adsRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return $response
            ->setPreviousUrl(route('ads.index'))
            ->setNextUrl(route('ads.edit', $ads->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $ads = $this->adsRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $ads));

        page_title()->setTitle(trans('plugins/ads::ads.edit') . ' "' . $ads->name . '"');

        return $formBuilder->create(AdsForm::class, ['model' => $ads])->renderForm();
    }

    /**
     * @param int $id
     * @param AdsRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, AdsRequest $request, BaseHttpResponse $response)
    {
        $ads = $this->adsRepository->findOrFail($id);

        $ads->fill($request->input());

        $this->adsRepository->createOrUpdate($ads);

        event(new UpdatedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

        return $response
            ->setPreviousUrl(route('ads.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $ads = $this->adsRepository->findOrFail($id);

            $this->adsRepository->delete($ads);

            event(new DeletedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $ads = $this->adsRepository->findOrFail($id);
            $this->adsRepository->delete($ads);
            event(new DeletedContentEvent(ADS_MODULE_SCREEN_NAME, $request, $ads));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
