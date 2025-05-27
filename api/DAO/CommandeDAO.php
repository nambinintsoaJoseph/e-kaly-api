<?php 

namespace App\DAO;

use App\Models\Commande; 
use App\Services\DatabaseConnection;

class CommandeDAO
{
    private $db; 

    public function __construct(DatabaseConnection $db) 
    {
        $this->db = $db;
    }

    public function create(Commande $commande): ?int 
    {
        $conn = $this->db->getDatabase(); 
        $sql = "INSERT INTO commande(id_utilisateur) VALUES(:id_utilisateur)"; 

        $stmt = oci_parse($conn, $sql); 

        $id_utilisateur = $commande->getId_utilisateur(); 

        oci_bind_by_name($stmt, ':id_utilisateur', $id_utilisateur); 
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