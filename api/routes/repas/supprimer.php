<?php
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\Services\DatabaseConnection;
use App\DAO\RepasDAO;
use App\Models\Repas; 
use App\Services\JWTManager;
use App\Services\BearerService; 

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') 
{
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
    if ($payload['role'] !== 'gerant') 
    {
        http_response_code(403);
        echo json_encode([
            "success" => false, 
            "message" => "Accès refusé, vous ne pouvez pas effectuer cette action."
        ]);
        exit;
    }

    if(
        isset($_GET['id_repas'])
    ) 
    {
        $databaseConnection = new DatabaseConnection(); 
        $repas = new Repas();
        $repasDAO = new RepasDAO($databaseConnection); 

        $repas->setId_repas($_GET['id_repas']);
        $repas->setId_utilisateur($payload['id_utilisateur']); 

        if($repasDAO->delete($repas)) 
        {
            http_response_code(200); 
            echo json_encode([
                "success" => true, 
                "message" => "Repas supprimé avec succès."
            ]); 
        }
        else 
        {
            http_response_code(500); 
            echo json_encode([
                "success" => false, 
                "message" => "Erreur de la suppression du repas."
            ]); 
        }
    }
    else 
    {
        http_response_code(400); 
        echo json_encode([
            "success" => false,
            "message" => "Données manquant."
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
