<?php

namespace LennisDev\XAuthClient;

require_once __DIR__ . "/Token.php";

use LennisDev\XAuthClient\Token;

class Auth
{
    public static function genAuthRequest(string $endpoint, string $redirect, array $scopes = ["identify"], string $name = "", string $icon = ""): string
    {
        $param = array(
            "name" => $name,
            "redirect" => $redirect,
            "scopes" => $scopes,
            "icon" => $icon
        );
        return "https://" . $endpoint . "/authorize#" . base64_encode(json_encode($param));
    }

    public static function identify(): string
    {
        return self::identifyToken($_POST["token"]);
    }

    public static function identifyToken(string $token): string
    {
        $tokenObj = new Token($token);
        return $tokenObj->getUsername() . "@" . $tokenObj->getAuthServer();
    }
}
