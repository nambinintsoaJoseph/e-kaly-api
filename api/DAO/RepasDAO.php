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

    public function update(Repas $repas): bool 
    {
        $conn = $this->db->getDatabase();

        // Vérifie d'abord que le repas appartient à l'utilisateur
        $sqlCheck = "SELECT COUNT(*) FROM Repas WHERE id_repas = :id_repas AND id_utilisateur = :id_utilisateur";
        $stmtCheck = oci_parse($conn, $sqlCheck);

        $id_repas = $repas->getId_repas(); 
        $id_utilisateur = $repas->getId_utilisateur(); 

        oci_bind_by_name($stmtCheck, ':id_repas', $id_repas);
        oci_bind_by_name($stmtCheck, ':id_utilisateur', $id_utilisateur);
        
        if (!oci_execute($stmtCheck)) 
        {
            error_log("Erreur vérification propriétaire: " . oci_error($stmtCheck)['message']);
            return false;
        }
        
        $count = oci_fetch_array($stmtCheck)[0];
        oci_free_statement($stmtCheck);
        
        if ($count == 0) 
        {
            return false; // Le repas n'appartient pas à l'utilisateur
        }

        $sql = "UPDATE Repas SET 
                nom = :nom, 
                description = :description, 
                prix = :prix 
                WHERE id_repas = :id_repas";
        
        $stmt = oci_parse($conn, $sql);
        
        $id_repas = $repas->getId_repas(); 
        $nom = $repas->getNom(); 
        $description = $repas->getDescription(); 
        $prix = $repas->getPrix(); 
        
        oci_bind_by_name($stmt, ':id_repas', $id_repas);
        oci_bind_by_name($stmt, ':nom', $nom);
        oci_bind_by_name($stmt, ':description', $description);
        oci_bind_by_name($stmt, ':prix', $prix);
        
        $success = oci_execute($stmt);
        
        if (!$success) {
            error_log("Erreur mise à jour repas: " . oci_error($stmt)['message']);
        }
        
        oci_free_statement($stmt);
        return $success;
    }
}