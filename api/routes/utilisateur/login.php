<?php 

require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

use App\Services\DatabaseConnection; 
use App\DAO\UtilisateurDAO;
use App\Services\JWTManager; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{   
    $body = json_decode(file_get_contents("php://input"));

    if(
        isset($body->email) && 
        isset($body->mot_passe)
    )
    {
        $databaseConnection = new DatabaseConnection(); 
        $utilisateurDAO = new UtilisateurDAO($databaseConnection); 

        $utilisateur = $utilisateurDAO->authenticate($body->email, $body->mot_passe); 

        if($utilisateur) 
        {
            // Générer un token 
            $jwtManager = new JWTManager(); 
            $payload = [
                'id_utilisateur' => $utilisateur->getId_utilisateur(), 
                'nom' => $utilisateur->getNom(), 
                'prenom' => $utilisateur->getPrenom(), 
                'email' => $utilisateur->getEmail(),
                'role' => $utilisateur->getRole()
            ];
            $jwt = $jwtManager->createToken($payload);

            http_response_code(200);
            echo json_encode([
                "success" => true, 
                "token" => $jwt
            ]);
        }
        else 
        {
            http_response_code(401);
            echo json_encode([
                "success" => false, 
                "message" => "Echec d'authentification."
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
