<?php
class Chatwork
{
    public $message;
    public $roomId;
    public $apiToken;

    const MESSAGE_END_POINT = "https://api.chatwork.com/v1/rooms/%s/messages";
    const MEMBER_END_POINT = "https://api.chatwork.com/v1/rooms/%s/members";

    public function __construct($apiToken, $roomId, $message = '')
    {
        $this->apiToken = $apiToken;
        $this->roomId = $roomId;
        $this->message = $message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function send($url, $type = null, $params = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-ChatWorkToken: ' . $this->apiToken]);

        if ($type) {
            curl_setopt($ch, $type, 1);
        }

        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    /**
     * Send message to chatwork group
     * @internal param Access $api_token token
     * @internal param Room $room_id id
     * @internal param Message $message
     * @return mixed
     */
    public function sendMessage()
    {
        $params = [
            'body' => $this->message,
        ];

        $url = sprintf(self::MESSAGE_END_POINT, $this->roomId);
        return $this->send($url, CURLOPT_POST, $params);
    }

    public function sendReply($userId, $username, $messageId)
    {
        $message = "[rp aid={$userId} to={$this->roomId}-{$messageId}] $username\n";
        $params = [
            'body' => $message . $this->message,
        ];

        $url = sprintf(self::MESSAGE_END_POINT, $this->roomId);
        return $this->send($url, CURLOPT_POST, $params);
    }

    public function getMessages()
    {
        $url = sprintf(self::MESSAGE_END_POINT, $this->roomId);
        $url .= '?force=1';
        return $this->send($url);
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        $url = sprintf(self::MEMBER_END_POINT, $this->roomId);
        return $this->send($url);
    }
}