<?php 

namespace Viipers\FcmNotification;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class FcmNotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Notification::resolved(function (ChannelManager $service) {
        //     $service->extend('fcm', function ($app) {
        //         return new FcmChannel();
        //     });
        // });
        dd('test');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }   
}
