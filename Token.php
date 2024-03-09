<?php

namespace LennisDev\XAuthClient;

class Token
{
    private string $token;
    private array $data = array();
    private string $sign;
    private string $server;
    private string $username;

    public function __construct(string $token)
    {
        $this->token = $token;
        $explode = explode(".", $this->token);
        if (count($explode) !== 2) throw new \Exception("Invalid token");

        $this->data = json_decode(base64_decode($explode[0]), true);
        $this->sign = $explode[1];

        $this->server = $this->data["server"];

        $url = "https://" . $this->server . "/api/v1/identify.php";
        $options = array(
            'http' => array(
                'method' => 'POST',
                'content' => json_encode(array("token" => $this->token)),
                'header' => "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);
        if ($response["success"] !== true) throw new \Exception("Invalid token");
        $this->username = $this->data["username"];
    }

    public function getAuthServer(): string
    {
        return $this->server;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
