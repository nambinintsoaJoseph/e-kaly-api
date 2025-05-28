<?php 

namespace App\DAO;

use App\Models\CommandeRepas;
use App\Services\DatabaseConnection; 

class CommandeRepasDAO 
{
    private $db; 

    public function __construct(DatabaseConnection $db) 
    {
        $this->db = $db;
    }

    public function create(CommandeRepas $commandeRepas): ?int
    {
        $conn = $this->db->getDatabase(); 
        $sql = "INSERT INTO commanderepas(id_repas, id_commande, quantite) VALUES(:id_repas, :id_commande, :quantite)";

        $stmt = oci_parse($conn, $sql); 

        $id_repas = $commandeRepas->getId_repas(); 
        $id_commande = $commandeRepas->getId_commande(); 
        $quantite = $commandeRepas->getQuantite(); 

        oci_bind_by_name($stmt, ':id_repas', $id_repas); 
        oci_bind_by_name($stmt, ':id_commande', $id_commande); 
        oci_bind_by_name($stmt, ':quantite', $quantite); 
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