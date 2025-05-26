<?php 

// Pour récuperer le token (Authorization Bearer ... )

namespace App\Services;

class BearerService
{
    public function getBearerToken() 
    {
        $headers = null;

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }
        elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_change_key_case($requestHeaders, CASE_UPPER);
            if (isset($requestHeaders['AUTHORIZATION'])) {
                $headers = trim($requestHeaders['AUTHORIZATION']);
            }
        }

        if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }

        return null; 
    }
}
