<?php
namespace App\DAO;

use App\Models\Repas;
use App\Services\DatabaseConnection;

class RepasDAO {
    private $db;

    public function __construct(DatabaseConnection $db) 
    {
        $this->db = $db;
    }

    public function create(Repas $repas): ?int 
    {
        $conn = $this->db->getDatabase();
        $sql = "INSERT INTO Repas (id_utilisateur, nom, description, photo, prix) 
                VALUES (:id_utilisateur, :nom, :description, :photo, :prix)";

        $stmt = oci_parse($conn, $sql);

        $id_utilisateur = $repas->getId_utilisateur();
        $nom = $repas->getNom(); 
        $description = $repas->getDescription(); 
        $photo = $repas->getPhoto(); 
        $prix = $repas->getPrix(); 

        oci_bind_by_name($stmt, ':id_utilisateur', $id_utilisateur);
        oci_bind_by_name($stmt, ':nom', $nom);
        oci_bind_by_name($stmt, ':description', $description);
        oci_bind_by_name($stmt, ':photo', $photo);
        oci_bind_by_name($stmt, ':prix', $prix);

        $result = oci_execute($stmt); 

        if($result) 
        {
            oci_commit($conn); 
        }
        else 
        {
            $error = oci_error($stmt); 
            error_log("Erreur oci : " . $error['message']); 
        }

        oci_free_statement($stmt); 
        return $result; 
    }
}