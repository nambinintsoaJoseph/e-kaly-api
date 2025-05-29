<?php 

namespace App\Services;

class DatabaseConnection 
{
    private $user; 
    private $password; 
    private $connection;

    public function __construct()
    {
        $this->loadEnv();
        $this->user = getenv('DB_USER'); 
        $this->password = getenv('DB_PASSWORD'); 
        $this->connection = getenv('DB_CONNECTION'); 
    }

    private function loadEnv(): void
    {
        if (!file_exists(__DIR__.'/../../.env')) {
            throw new \RuntimeException('Fichier .env manquant');
        }

        $lines = file(__DIR__.'/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            
            putenv($line); // Charge chaque variable
        }
    }

    public function getDatabase() 
    {
        if(function_exists('oci_connect')) 
        {
            $conn = oci_connect($this->user, $this->password, $this->connection);
            return $conn; 
        }

        throw new \Exception("Activer l'extension oci8. "); 
    }
}