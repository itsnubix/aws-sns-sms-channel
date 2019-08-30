<?php

namespace Nubix\Notifications\Channels;

use Aws\Sns\SnsClient;
use Illuminate\Notifications\Notification;
use Nubix\Notifications\Messages\SmsMessage;

class SmsChannel
{
    /**
     * The SNS client instance.
     *
     * @var \Aws\Sns\SnsClient
     */
    protected $sns;

    public function __construct(SnsClient $sns)
    {
        $this->sns = $sns;
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Aws\Result
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('sms', $notification)) {
            return;
        }

        if (!$result = $this->sns->checkIfPhoneNumberIsOptedOut(['phoneNumber' => $to])) {
            return;
        }

        if ($result['isOptedOut']) {
            return;
        }

        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            $message = new SmsMessage($message);
        }

        return $this->sns->publish([
            'Message' => $message->content,
            'PhoneNumber' => $to,
        ]);
    }
}
