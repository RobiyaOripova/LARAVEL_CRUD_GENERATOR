<?php

namespace Modules\Playmobile\Components;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Modules\Playmobile\Entities\SendSms;
use Psy\Util\Json;

class PlaymobileSend
{
    /**
     * @throws GuzzleException
     */
    public static function sendSms($recipient, $text): void
    {
        $message_id = date('YmdHis');

        $message = [
            'messages' => [
                [
                    'recipient' => $recipient,
                    'message-id' => $message_id,
                    'sms' => [
                        'originator' => config('system.playmobile.PLAYMOBILE_ORIGINATOR'),
                        'content' => [
                            'text' => $text,
                        ],
                    ],
                ],
            ],
        ];
        SendSms::create([
            'recipient' => $recipient,
            'originator' => config('system.playmobile.PLAYMOBILE_ORIGINATOR'),
            'message_id' => $message_id,
            'text' => $text,
            'status' => User::STATUS_ACTIVE,
        ]);

        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        try {
            $client->request('POST', 'http://91.204.239.44/broker-api/send', [
                'auth' => [config('playmobile.PLAYMOBILE_USERNAME'), config('playmobile.PLAYMOBILE_PASSWORD')],
                'body' => Json::encode($message),
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }
}
