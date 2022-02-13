<?php

Route::group(['namespace' => 'Tec\Ads\Http\Controllers', 'middleware' => ['web']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'ads', 'as' => 'ads.'], function () {
            Route::resource('', 'AdsController')->parameters(['' => 'ads']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AdsController@deletes',
                'permission' => 'ads.destroy',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get('ads-click/{key}', [
                'as'   => 'public.ads-click',
                'uses' => 'PublicController@getAdsClick',
            ]);
        });
    }

});
