<?php
class Chatwork
{
    public $message;
    public $roomId;
    public $uri;
    public $apiToken;

    public function __construct($apiToken, $roomId, $message)
    {
        $this->apiToken = $apiToken;
        $this->roomId = $roomId;
        $this->message = $message;
        $this->uri = "https://api.chatwork.com/v1/rooms/{$roomId}/messages";
    }

    /**
     * Send message to chatwork group
     * @internal param Access $api_token token
     * @internal param Room $room_id id
     * @internal param Message $message
     * @return mixed
     */
    function sendMessage()
    {
        $params = [
            'body' => $this->message,
        ];
        // Init cURL session
        $ch = curl_init();
        // Set Options on the cURL session
        // Set the URL to fetch
        curl_setopt($ch, CURLOPT_URL, sprintf($this->uri, $this->roomId));
        // Set HTTP header
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-ChatWorkToken: ' . $this->apiToken]);
        // Set method to POST
        curl_setopt($ch, CURLOPT_POST, 1);
        // Set data to post
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));
        // Set return the transfer as a string  of the return value of curl_exec()
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Perform cURL session
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);

        return $response;
    }
}