<?php
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\DAO\CommandeDAO;
use App\Models\Commande;
use App\Services\JWTManager; 
use App\Services\BearerService;
use App\Services\DatabaseConnection;

if($_SERVER['REQUEST_METHOD'] == 'POST') 
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
    $commande = new Commande(); 
    $commandeDAO = new CommandeDAO($databaseConnection); 

    $commande->setId_utilisateur($payload['id_utilisateur']); 

    if($commandeDAO->create($commande))
    {
        http_response_code(200); 
        echo json_encode([
            "success" => true, 
            "message" => "Commande créée avec succès."
        ]); 
    }
    else 
    {
        http_response_code(500); 
        echo json_encode([
            "success" => false, 
            "message" => "Erreur de la création du commande."
        ]); 
    }
}
else 
{
    http_response_code(405); 
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée."
    ]); 
}
