<?php
require __DIR__ . '/../../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\Models\Repas;
use App\DAO\RepasDAO;
use App\Services\DatabaseConnection;
use App\Services\JWTManager;
use App\Services\BearerService; 

// Si la méthode n'est pas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Méthode non autorisée"
    ]);
    exit;
}

// Vérification du token 
$bearerService = new BearerService();  
$token = $bearerService->getBearerToken();

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

// Traitement du fichier image
$uploadDir = __DIR__ . '/../../../uploads/repas/';
if (!file_exists($uploadDir)) 
{
    mkdir($uploadDir, 0777, true);
}

$photoName = null;
if (isset($_FILES['photo'])) 
{
    $file = $_FILES['photo'];
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $photoName = uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $photoName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) 
    {
        http_response_code(500);
        echo json_encode([
            "success" => false, 
            "message" => "Erreur lors de l'upload de l'image"
        ]);
        exit;
    }
}

// Création du repas
try 
{
    $database = new DatabaseConnection();
    $repasDAO = new RepasDAO($database);
    $repas = new Repas();

    $repas->setId_utilisateur($payload['id_utilisateur'])
          ->setNom($_POST['nom'])
          ->setDescription($_POST['description'])
          ->setPhoto($photoName)
          ->setPrix($_POST['prix']);

    if($repasDAO->create($repas))
    {
        http_response_code(201);
        echo json_encode([
            "sucess" => true,
            "message" => "Repas créé avec succès",
        ]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode([
            "success" => false, 
            "message" => "Erreur lors de la création du repas"
        ]);
    }
} catch (Exception $e) 
{
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur serveur: " . $e->getMessage()
    ]);
}
