<?php

namespace Viipers\FcmNotifLaravel;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    private $client;
    private $apiKey;

    /**
     * FcmChannel constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param string $apiKey
     */
    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toFcm($notifiable);

        if (is_null($message->getTo()) && is_null($message->getCondition())) {
            if (!$to = $notifiable->routeNotificationFor('fcm', $notification)) {
                return;
            }

            $message->to($to);
        }

        $response = [];

        $headers = [
            'Authorization' => 'key=' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        // Check if the 'to' field in the FCM message is an array (supports multicast)
        if (is_array($message->getTo)) {
            // Split the 'to' array into chunks of 1000 devices (maximum allowed by FCM)
            $chunks = array_chunk($message->getTo(), 1000);

            // Iterate through each chunk and send separate requests
            foreach ($chunks as $chunk) {
                $message->setTo($chunk);

                // Send the FCM API request and decode the response
                $respons = $this->client->post(
                    'https://fcm.googleapis.com/fcm/send',
                    [
                        'headers' => $headers,
                        'json' => $message->toArray(),
                    ]
                );

                array_push($response, json_decode($respons->getBody(), true));
            }
        } else {
            $respons = $this->client->post(
                'https://fcm.googleapis.com/fcm/send',
                [
                    'headers' => $headers,
                    'json' => $message->toArray(),
                ]
            );

            // Push the decoded response to the response array
            array_push($response, json_decode($respons->getBody(), true));
        }

        // Return the response array
        return $response;
    }
}
