<?php 

namespace App\Services;

class JWTManager 
{
    private $secretKey;

    public function __construct()
    {
         $this->loadEnv();
        $this->secretKey = getenv('JWT_SECRET_KEY');
    }

    public function createToken($payload)
    {
        $base64UrlHeader = $this->base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        $base64UrlSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = $this->base64UrlEncode($base64UrlSignature);
        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    private function loadEnv(): void
    {
        if (!file_exists(__DIR__.'/../../.env')) {
            throw new \RuntimeException('Fichier .env manquant');
        }

        if (!getenv('JWT_SECRET_KEY')) {
            $lines = file(__DIR__.'/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                putenv($line);
            }
        }
    }

    private function base64UrlEncode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    private function base64UrlDecode($data)
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }

    public function validateToken($token)
    {
        // Implementation for validating JWT
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);

        $signature = $this->base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);

        return hash_equals($signature, $expectedSignature);
    }

    public function decodeToken($token)
    {
        // Implementation for decoding JWT
        list(, $base64UrlPayload, ) = explode('.', $token);
        $payload = $this->base64UrlDecode($base64UrlPayload);
        return json_decode($payload, true);
    }
}
