<?php

namespace Tec\Ads\Providers;

use AdsManager;
use Tec\Ads\Facades\AdsManagerFacade;
use Tec\Ads\Models\Ads;
use Tec\Ads\Repositories\Caches\AdsCacheDecorator;
use Tec\Ads\Repositories\Eloquent\AdsRepository;
use Tec\Ads\Repositories\Interfaces\AdsInterface;
use Tec\Base\Supports\Helper;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

class AdsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(AdsInterface::class, function () {
            return new AdsCacheDecorator(new AdsRepository(new Ads));
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('AdsManager', AdsManagerFacade::class);
    }

    public function boot()
    {
        $this->setNamespace('plugins/ads')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-ads',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/ads::ads.name',
                'icon'        => 'fas fa-bullhorn',
                'url'         => route('ads.index'),
                'permissions' => ['ads.index'],
            ]);
        });

        if (function_exists('shortcode')) {
            add_shortcode('ads', 'Ads', 'Ads', function ($shortcode) {
                if (!$shortcode->key) {
                    return null;
                }

                return AdsManager::displayAds((string)$shortcode->key);
            });
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Ads::class, [
                'name',
                'image',
                'url',
            ]);
        }
    }
}
