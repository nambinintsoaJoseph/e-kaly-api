<?php
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\Models\Repas; 
use App\DAO\RepasDAO; 
use App\Services\DatabaseConnection; 
use App\Services\BearerService; 
use App\Services\JWTManager; 

if($_SERVER['REQUEST_METHOD'] == 'GET') 
{
    $body = json_decode(file_get_contents("php://input")); 

    // Récuperation du token 
    $bearerService = new BearerService(); 
    $token = $bearerService->getBearerToken();

    // Validation du token 
    $jwtManager = new JWTManager(); 
    if (!$jwtManager->validateToken($token)) 
    {
        http_response_code(401);
        echo json_encode([
            "success" => false, 
            "message" => "Token invalide ou expiré"
        ]);
        exit;
    }

    // Vérification du rôle 
    $payload = $jwtManager->decodeToken($token);
    if ($payload['role'] !== 'agent') 
    {
        http_response_code(403);
        echo json_encode([
            "success" => false, 
            "message" => "Accès refusé, vous ne pouvez pas effectuer cette action."
        ]);
        exit;
    }

    $databaseConnection = new DatabaseConnection(); 
    $repasDAO = new RepasDAO($databaseConnection); 
    $listeRepas = $repasDAO->getAll(); 
    
    // Formatage de la réponse
    $reponse = array_map(function($repas) {
        return [
            'id_repas' => $repas->getId_repas(),
            'nom' => $repas->getNom(),
            'description' => $repas->getDescription(),
            'photo' => $repas->getPhoto() ? '/uploads/repas/' . $repas->getPhoto() : null,
            'prix' => $repas->getPrix()
        ];
    }, $listeRepas);

    http_response_code(200); 
    echo json_encode([
        "success" => true, 
        "repas" => $reponse
    ]); 
}
else 
{
    http_response_code(405); 
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée."
    ]); 
}
