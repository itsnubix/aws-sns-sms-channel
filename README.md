# AWS SNS SMS Notifications Channel for Laravel

[![Total Downloads](https://img.shields.io/packagist/dt/itsnubix/aws-sns-sms-channel.svg?style=flat-square)](https://packagist.org/packages/itsnubix/aws-sns-sms-channel)
[![Build Status](https://travis-ci.org/itsnubix/aws-sns-sms-channel.svg?branch=master)](https://travis-ci.org/itsnubix/aws-sns-sms-channel/)
[![Latest Stable Version](https://poser.pugx.org/itsnubix/aws-sns-sms-channel/v/stable.svg)](https://poser.pugx.org/itsnubix/aws-sns-sms-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Installation

`composer require itsnubix/aws-sns-sms-channel`

In your `config/services.php` file enter:

```php
'sns' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('SNS_DEFAULT_REGION'),
],
```

Notice here that the region is not necessarily your standard `AWS_DEFAULT_REGION` as only certain regions allow SMS messages to be sent from them. [Click here](https://docs.aws.amazon.com/sns/latest/dg/sns-supported-regions-countries.html) for a list of nodes that allow SMS.

Be sure that the user who owns your access key and secret has at least the following policy on AWS IAM:

```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "AllowSendingSMSMessages",
      "Effect": "Allow",
      "Action": [
        "sns:Publish",
        "sns:SetSMSAttributes",
        "sns:CheckIfPhoneNumberIsOptedOut"
      ],
      "Resource": ["*"]
    }
  ]
}
```

Now in your notifications you can do the following:

```php
<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Nubix\Notifications\Messages\SmsMessage;

class SendHelloText extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['sms'];
    }
    /**
     * Get the SMS representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \App\Channels\Messages\SmsMessage
     */
    public function toSms($notifiable)
    {
        return (new SmsMessage())
            ->content('Hello world');
    }
}
```
