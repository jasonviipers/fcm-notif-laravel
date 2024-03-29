<?php 

namespace Viipers\FcmNotifLaravel;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class FcmNotifLaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('fcm', function ($app) {
                return new FcmChannel(app(Client::class), config('services.fcm.key'));
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(FcmChannel::class, function () {
            return new FcmChannel(app(Client::class), config('services.fcm.key'));
        });
    }   
}