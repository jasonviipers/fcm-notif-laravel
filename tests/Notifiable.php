<?php

namespace Viipers\FcmNotifLaravel\Tests;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForFcm()
    {
        return 1;
    }
}