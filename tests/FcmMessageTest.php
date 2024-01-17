<?php

namespace Viipers\FcmNotifLaravel\Tests;

use Viipers\FcmNotifLaravel\FcmMessage;

class FcmMessageTest extends TestCase
{
    /** @var FcmMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();
        $this->message = new FcmMessage();
    }

    /** @test */
    public function it_has_default_priority()
    {
        $this->assertEquals(FcmMessage::PRIORITY_NORMAL, $this->message->getPriority());
    }

    /** @test */
    public function it_can_set_to_value()
    {
        $this->message->to('example-token');
        $this->assertEquals('example-token', $this->message->getTo());
    }

    /** @test */
    public function it_can_set_notification_details()
    {
        $notification = [
            'title' => 'Test Title',
            'body' => 'Test Body',
        ];

        $this->message->notification($notification);

        $this->assertEquals($notification, $this->message->getNotification());
    }

    /** @test */
    public function it_can_set_custom_data()
    {
        $data = ['key' => 'value'];

        $this->message->data($data);

        $this->assertEquals($data, $this->message->getData());
    }

    // Add more test cases for other methods as needed...

    /** @test */
    public function it_can_convert_to_array()
    {
        $this->message->to('example-token');
        $this->message->notification(['title' => 'Test Title', 'body' => 'Test Body']);
        $this->message->data(['key' => 'value']);
        $this->message->priority(FcmMessage::PRIORITY_HIGH);

        $expectedArray = [
            'to' => 'example-token',
            'notification' => ['title' => 'Test Title', 'body' => 'Test Body'],
            'data' => ['key' => 'value'],
            'priority' => FcmMessage::PRIORITY_HIGH,
        ];

        $this->assertEquals($expectedArray, $this->message->toArray());
    }
}
