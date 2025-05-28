<?php
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\DAO\CommandeRepasDAO;
use App\Models\CommandeRepas;
use App\Services\BearerService;
use App\Services\DatabaseConnection;
use App\Services\JWTManager;

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

    if(
        isset($body->id_repas) && 
        isset($body->id_commande) && 
        isset($body->quantite)
    ) 
    {
        $databaseConnection = new DatabaseConnection(); 
        $commandeRepasDAO = new CommandeRepasDAO($databaseConnection);
        $commandeRepas = new CommandeRepas(); 

        $commandeRepas->setId_repas($body->id_repas); 
        $commandeRepas->setId_commande($body->id_commande); 
        $commandeRepas->setQuantite($body->quantite); 

        if($commandeRepasDAO->create($commandeRepas)) 
        {
            http_response_code(201); 
            echo json_encode([
                "success" => true, 
                "message" => "Repas ajouté à la commande."
            ]); 
        }
        else 
        {
            http_response_code(500);
            echo json_encode([
                "success" => false, 
                "message" => "Erreur lors de l'ajout du repas dans la commande."
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
}
else 
{
    http_response_code(405); 
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée."
    ]); 
}
