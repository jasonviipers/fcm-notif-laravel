<?php

namespace Viipers\FcmNotifLaravel;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Psr\Http\Message\ResponseInterface;

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
    public function __construct(Client $client, string $apiKey)
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
     * @return array
     */
    public function send($notifiable, Notification $notification): array
    {
        $message = $notification->toFcm($notifiable);
        $response = [];

        $headers = [
            'Authorization' => 'key=' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        // Check if the 'to' field in the FCM message is an array (supports multicast)
        if (is_array($message->getTo())) {
            $this->sendMulticastNotifications($message, $headers, $response);
        } else {
            $this->sendSingleNotification($message, $headers, $response);
        }

        return $response;
    }

    /**
     * Send a single FCM notification.
     *
     * @param \Viipers\FcmNotifLaravel\FcmMessage $message
     * @param array $headers
     * @param array $response
     */
    private function sendSingleNotification(FcmMessage $message, array $headers, array &$response): void
    {
        $respons = $this->client->post(
            self::FCM_API_URL,
            [
                'headers' => $headers,
                'json' => $message->toArray(),
            ]
        );

        $response[] = $this->decodeResponse($respons);
    }

    /**
     * Send multicast FCM notifications.
     *
     * @param \Viipers\FcmNotifLaravel\FcmMessage $message
     * @param array $headers
     * @param array $response
     */
    private function sendMulticastNotifications(FcmMessage $message, array $headers, array &$response): void
    {
        $chunks = array_chunk($message->getTo(), 1000);

        foreach ($chunks as $chunk) {
            $message->to($chunk, false); // Set the 'to' field to the current chunk
            $respons = $this->client->post(
                self::FCM_API_URL,
                [
                    'headers' => $headers,
                    'json' => $message->toArray(),
                ]
            );

            $response[] = $this->decodeResponse($respons);
        }
    }

    /**
     * Decode the FCM API response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array
     */
    private function decodeResponse(ResponseInterface $response): array
    {
        return json_decode($response->getBody(), true);
    }
}
