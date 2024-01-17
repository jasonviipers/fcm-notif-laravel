<?php

namespace Viipers\FcmNotifLaravel;

class FcmMessage
{
    private $to;
    private $notification;
    private $data;
    private $priority = self::PRIORITY_NORMAL;
    private $headers = [];
    private $timeToLive;
    private $condition;
    private $collapseKey;
    private $contentAvailable;
    private $mutableContent;
    private $dryRun;

    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    /**
     * FcmMessage constructor.
     *
     * @param string|array $to
     */
    public function __construct($to = null)
    {
        $this->to = $to;
    }

    /**
     * Set the 'to' value of the FCM message.
     * @param bool $recipientIsTopic
     * @param string|array $to
     *
     * @return $this
     */
    public function to($recipients, $recipientIsTopic = false)
    {
        if ($recipientIsTopic && is_string($recipients)) {
            $this->to = '/topics/' . $recipients;
        } elseif (is_array($recipients) && count($recipients) == 1) {
            $this->to = $recipients[0];
        } else {
            $this->to = $recipients;
        }

        return $this;
    }

    /**
     * Get the 'to' value of the FCM message.
     *
     * @return string|array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the notification details for the FCM message.
     *
     * @param array $params ['title' => '', 'body' => '', 'sound' => '', 'icon' => '', 'click_action' => '']
     *
     * @return $this
     */
    public function notification($params)
    {
        $this->notification = $params;

        return $this;
    }

    /**
     * Set custom data for the FCM message.
     *
     * @param mixed|null $data Custom data.
     *
     * @return $this
     */
    public function data($data = null)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the priority level for the FCM message.
     *
     * @param string $priority Priority level (use class constants).
     *
     * @return $this
     */
    public function priority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set the condition for the FCM message.
     *
     * @param string $condition The condition determining the target devices.
     *
     * @return $this
     */
    public function condition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Set the collapse key for the FCM message.
     *
     * @param string $collapseKey The collapse key for the FCM message.
     *
     * @return $this
     */
    public function collapseKey($collapseKey)
    {
        $this->collapseKey = $collapseKey;

        return $this;
    }

    /**
     * Set the content available flag for the FCM message.
     *
     * @param bool $contentAvailable The content available flag for the FCM message.
     *
     * @return $this
     */
    public function contentAvailable($contentAvailable)
    {
        $this->contentAvailable = $contentAvailable;

        return $this;
    }

    /**
     * Set the mutable content flag for the FCM message.
     *
     * @param bool $mutableContent The mutable content flag for the FCM message.
     *
     * @return $this
     */
    public function mutableContent($mutableContent)
    {
        $this->mutableContent = $mutableContent;

        return $this;
    }

    /**
     * Set the dry run flag for the FCM message.
     *
     * @param bool $dryRun The dry run flag for the FCM message.
     *
     * @return $this
     */
    public function dryRun($dryRun)
    {
        $this->dryRun = $dryRun;

        return $this;
    }

    /**
     * Set the time to live for the FCM message.
     *
     * @param int $timeToLive The time to live for the FCM message.
     *
     * @return $this
     */
    public function timeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;

        return $this;
    }

    /**
     * Get the notification details for the FCM message.
     *
     * @return array
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Get the custom data for the FCM message.
     *
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the setHeaders for the FCM message.
     * 
     * @param array $headers
     * @return $this
     */
    public function setHeaders($headers = [])
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the getHeaders for the FCM message.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * Get the priority level for the FCM message.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Convert the FCM message to an array for sending.
     * 
     * @return array
     */
    public function toArray()
    {
        $message = [];

        if ($this->to) {
            $message['to'] = $this->to;
        }

        if ($this->notification) {
            $message['notification'] = $this->notification;
        }

        if ($this->data) {
            $message['data'] = $this->data;
        }

        if ($this->priority) {
            $message['priority'] = $this->priority;
        }

        if ($this->condition) {
            $message['condition'] = $this->condition;
        }

        if ($this->collapseKey) {
            $message['collapse_key'] = $this->collapseKey;
        }

        if ($this->contentAvailable) {
            $message['content_available'] = $this->contentAvailable;
        }

        if ($this->mutableContent) {
            $message['mutable_content'] = $this->mutableContent;
        }

        if ($this->dryRun) {
            $message['dry_run'] = $this->dryRun;
        }

        if ($this->timeToLive) {
            $message['time_to_live'] = $this->timeToLive;
        }

        return $message;
    }
}
