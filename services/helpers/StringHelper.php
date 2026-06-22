<?php

class StringHelper
{
    public static function generateToken(int $lengthString = 12)
    {
        return bin2hex(random_bytes($lengthString));
    }

    public static function getUrlDoimanWithProtocol(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain = $_SERVER['HTTP_HOST'];
        return $protocol . $domain;
    }
}
