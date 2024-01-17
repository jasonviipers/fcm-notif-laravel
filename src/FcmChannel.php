<?php

namespace Viipers\FcmNotifLaravel;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    const FCM_API_URL = 'https://fcm.googleapis.com/fcm/send';
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
        if (is_array($message->getTo())) {
            // Split the 'to' array into chunks of 1000 devices (maximum allowed by FCM)
            $chunks = array_chunk($message->getTo(), 1000);

            // Iterate through each chunk and send separate requests
            foreach ($chunks as $chunk) {
                $message->setTo($chunk);

                // Send the FCM API request and decode the response
                $respons = $this->client->post(
                    self::FCM_API_URL,
                    [
                        'headers' => $headers,
                        'json' => $message->toArray(),
                    ]
                );

                $response[] = $this->decodeResponse($respons);
            }
        } else {
            $respons = $this->client->post(
                self::FCM_API_URL,
                [
                    'headers' => $headers,
                    'json' => $message->toArray(),
                ]
            );

            // Push the decoded response to the response array
            array_push($response, $this->decodeResponse($respons));
        }

        // Return the response array
        return $response;
    }

    /**
     * Decode the FCM API response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    private function decodeResponse($response)
    {
        return json_decode($response->getBody(), true);
    }
}
