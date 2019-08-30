<?php

namespace Nubix\Notifications;

use Aws\Sns\SnsClient;
use Aws\Credentials\Credentials;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Nubix\Notifications\Channels\SmsChannel;

class SmsChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sms', function ($app) {
                return new SmsChannel(
                    new SnsClient([
                        'version' => '2010-03-31',
                        'credentials' => new Credentials(
                            $this->app['config']['services.sns.key'],
                            $this->app['config']['services.sns.secret']
                        ),
                        'region' => $this->app['config']['services.sns.region'],
                    ])
                );
            });
        });
    }
}
