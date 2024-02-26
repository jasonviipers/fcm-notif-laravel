<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Viipers\FcmNotifLaravel\FcmChannel;
use Viipers\FcmNotifLaravel\FcmMessage;

class FcmChannelTest extends TestCase
{
    public function testSingleNotificationIsSent()
    {
        // Mock Guzzle client response
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('post')->willReturn(new Response(200, [], '{"message_id": "12345"}'));

        // Instantiate FcmChannel with mocked client
        $fcmChannel = new FcmChannel($mockClient, 'fake_api_key');

        // Create mock notification
        $notification = $this->createMock(Notification::class);
        $notification->method('toFcm')->willReturn(new FcmMessage('device_token'));

        // Send notification
        $response = $fcmChannel->send('user', $notification);

        // Assertion
        $this->assertEquals([['message_id' => '12345']], $response);
    }

}