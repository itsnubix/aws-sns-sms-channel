<?php

namespace Nubix\Tests\Notifications;

use Mockery;
use Aws\Sns\SnsClient;
use PHPUnit\Framework\TestCase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Nubix\Notifications\Channels\SmsChannel;
use Nubix\Notifications\Messages\SmsMessage;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function sms_is_sent_via_aws()
    {
        $notifiable = new TestNotifiable();
        $notification = new TestNotification();
        $channel = new SmsChannel($sns = Mockery::mock(SnsClient::class));

        $sns->shouldReceive('publish')->with([
            'Message' => $notification->message,
            'PhoneNumber' => $notifiable->phone_number,
        ]);

        $channel->send($notifiable, $notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public $phone_number = '1.321.555.1234';
}

class TestNotification extends Notification
{
    public $message = 'test message';

    public function toSms()
    {
        return new SmsMessage($this->message);
    }
}
