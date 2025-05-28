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

    /**
        * Récupère toutes les commandes d'un agent avec les repas associés
        * @param int $id_utiilsateur - ID de l'agent
        * @return array - Tableau structuré des commandes
     */
    public function getCommandesByClient(int $id_utilisateur): array 
    {
        $conn = $this->db->getDatabase();
        
        $sql = "SELECT 
                    c.id_commande,
                    TO_CHAR(c.date_commande, 'DD-MM-YYYY - HH24:MI:SS') as date_commande,
                    r.id_repas,
                    r.nom as repas_nom,
                    r.description,
                    r.photo,
                    r.prix,
                    cr.quantite,
                    u.nom as gerant_nom,
                    u.prenom as gerant_prenom
                FROM Commande c
                JOIN CommandeRepas cr ON c.id_commande = cr.id_commande
                JOIN Repas r ON cr.id_repas = r.id_repas
                JOIN Utilisateur u ON r.id_utilisateur = u.id_utilisateur
                WHERE c.id_utilisateur = :id_utilisateur
                ORDER BY c.date_commande DESC, c.id_commande";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id_utilisateur', $id_utilisateur);

        if (!oci_execute($stmt)) 
        {
            error_log("Erreur récupération commandes client: " . oci_error($stmt)['message']);
            return [];
        }

        $commandes = [];
        while ($row = oci_fetch_assoc($stmt)) 
        {
            $id_commande = $row['ID_COMMANDE'];
            
            if (!isset($commandes[$id_commande])) 
            {
                $commandes[$id_commande] = [
                    'id_commande' => $id_commande,
                    'date_commande' => $row['DATE_COMMANDE'],
                    'gerant' => $row['GERANT_NOM'] . ' ' . $row['GERANT_PRENOM'],
                    'repas' => [],
                    'total' => 0
                ];
            }
            
            $sousTotal = $row['PRIX'] * $row['QUANTITE'];
            
            $commandes[$id_commande]['repas'][] = [
                'id_repas' => $row['ID_REPAS'],
                'nom' => $row['REPAS_NOM'],
                'description' => $row['DESCRIPTION'],
                'photo' => $row['PHOTO'] ? '/uploads/repas/' . $row['PHOTO'] : null,
                'prix_unitaire' => $row['PRIX'],
                'quantite' => $row['QUANTITE'],
                'sous_total' => $sousTotal
            ];
            
            $commandes[$id_commande]['total'] += $sousTotal;
        }

        oci_free_statement($stmt);
        return array_values($commandes);
    }
}
