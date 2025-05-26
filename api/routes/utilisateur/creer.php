<?php 
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

use App\Models\Utilisateur; 
use App\DAO\UtilisateurDAO;
use App\Services\DatabaseConnection;

$databaseConnection = new DatabaseConnection(); 
$utilisateurDAO = new UtilisateurDAO($databaseConnection); 
$utilisateur = new Utilisateur(); 

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $body = json_decode(file_get_contents("php://input"));

    if(
        isset($body->nom) && 
        isset($body->prenom) && 
        isset($body->email) && 
        isset($body->mot_passe) && 
        isset($body->role)
    ) 
    {   
        // Hasher le mot de passe 
        $mot_pass_hash = password_hash($body->mot_passe, PASSWORD_DEFAULT);

        $utilisateur->setNom($body->nom); 
        $utilisateur->setPrenom($body->prenom); 
        $utilisateur->setEmail($body->email); 
        $utilisateur->setMot_passe($mot_pass_hash);
        $utilisateur->setRole($body->role);

        if($utilisateurDAO->create($utilisateur))
        {   
            echo json_encode([
                "success" => true,
                "message" => "Utilisateur créé avec succès.", 
            ]); 
        }
        else 
        {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Erreur de création de l'utilisateur."
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
