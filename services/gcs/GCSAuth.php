<?php

declare(strict_types=1);

class GCSAuth
{
    private array $credentials;

    public function __construct(string $pathToJson)
    {
        $this->credentials = json_decode(file_get_contents($pathToJson), true);
    }

    public function getAccessToken(): string
    {
        $now = time();

        $header = [
            "alg" => "RS256",
            "typ" => "JWT"
        ];

        $payload = [
            "iss" => $this->credentials['client_email'],
            "scope" => "https://www.googleapis.com/auth/devstorage.full_control",
            "aud" => $this->credentials['token_uri'],
            "iat" => $now,
            "exp" => $now + 3600
        ];

        $jwt = $this->encodeJWT($header, $payload);

        return $this->requestAccessToken($jwt);
    }

    private function encodeJWT(array $header, array $payload): string
    {
        $base64Header = $this->base64UrlEncode(json_encode($header));
        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        $signature = '';
        openssl_sign(
            $base64Header . "." . $base64Payload,
            $signature,
            $this->credentials['private_key'],
            'SHA256'
        );

        $base64Signature = $this->base64UrlEncode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    private function requestAccessToken(string $jwt): string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->credentials['token_uri'],
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded"
            ],
            CURLOPT_POSTFIELDS => http_build_query([
                "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
                "assertion" => $jwt
            ])
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("Error OAuth: " . curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['access_token'])) {
            throw new Exception("No se pudo obtener access token");
        }

        return $data['access_token'];
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}