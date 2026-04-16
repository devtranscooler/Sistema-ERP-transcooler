<?php

declare(strict_types=1);

class GCSUploader
{
    private string $bucket;
    private string $accessToken;

    public function __construct(string $bucket, string $accessToken)
    {
        $this->bucket = $bucket;
        $this->accessToken = $accessToken;
    }

    public function upload(string $filePath, string $destination): array
    {
        $url = "https://storage.googleapis.com/upload/storage/v1/b/{$this->bucket}/o?uploadType=media&name={$destination}";

        $fileData = file_get_contents($filePath);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->accessToken}",
                "Content-Type: application/octet-stream",
                "Content-Length: " . strlen($fileData)
            ],
            CURLOPT_POSTFIELDS => $fileData
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                "status" => false,
                "error" => curl_error($ch)
            ];
        }

        return [
            "status" => true,
            "response" => json_decode($response, true)
        ];
    }
}