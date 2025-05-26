<?php 

namespace App\DAO; 
use App\Models\Utilisateur;
use App\Services\DatabaseConnection; 

class UtilisateurDAO 
{
    private $db; 
    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getById(int $id): ?Utilisateur 
    {
        $conn = $this->db->getDatabase();
        $sql = "SELECT id_utilisateur, nom, email, role FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id);
        
        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            throw new \RuntimeException("Erreur Oracle [getById]: " . $error['message']);
        }

        $userData = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $userData ? new Utilisateur($userData) : null;
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create(Utilisateur $utilisateur): int 
    {
        $conn = $this->db->getDatabase();
        $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_passe, role) 
                VALUES (:nom, :prenom, :email, :mot_passe, :role)";
    
        $stmt = oci_parse($conn, $sql); 
        
        $nom = $utilisateur->getNom();
        $prenom = $utilisateur->getPrenom();
        $email = $utilisateur->getEmail();
        $mot_passe = $utilisateur->getMot_passe();
        $role = $utilisateur->getRole();

        oci_bind_by_name($stmt, ':nom', $nom);
        oci_bind_by_name($stmt, ':prenom', $prenom);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':mot_passe', $mot_passe);
        oci_bind_by_name($stmt, ':role', $role);
        
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
     * Met à jour un utilisateur
     */
    public function update(Utilisateur $utilisateur): bool 
    {
        $conn = $this->db->getDatabase();
        $sql = "UPDATE utilisateur SET 
                nom = :nom, 
                prenom = :prenom,
                email = :email, 
                role = :role 
                WHERE id = :id";
        
        $stmt = oci_parse($conn, $sql);
        
        oci_bind_by_name($stmt, ':id', $utilisateur->getId_utilisateur());
        oci_bind_by_name($stmt, ':nom', $utilisateur->getNom());
        oci_bind_by_name($stmt, ':prenom', $utilisateur->getPrenom());
        oci_bind_by_name($stmt, ':email', $utilisateur->getEmail());
        oci_bind_by_name($stmt, ':role', $utilisateur->getRole());

        $success = oci_execute($stmt);
        if (!$success) 
        {
            $error = oci_error($stmt);
            throw new \RuntimeException("Erreur Oracle [update]: " . $error['message']);
        }

        oci_free_statement($stmt);
        return $success;
    }
    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool 
    {
        $conn = $this->db->getDatabase();
        
        $transaction = false;
        if (!oci_get_implicit_resultset($conn)) 
        {
            $transaction = true;
            oci_execute(oci_parse($conn, "BEGIN"));
        }

        try 
        {
            $sql = "DELETE FROM utilisateurs WHERE id = :id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':id', $id);

            if (!oci_execute($stmt)) 
            {
                throw new \RuntimeException("Erreur Oracle [delete]: " . oci_error($stmt)['message']);
            }

            // Valider la transaction si elle a été démarrée
            if ($transaction) 
            {
                oci_execute(oci_parse($conn, "COMMIT"));
            }

            return (oci_num_rows($stmt) > 0);
        } catch (\RuntimeException $e) 
        {
            // Annuler en cas d'erreur
            if ($transaction) 
            {
                oci_execute(oci_parse($conn, "ROLLBACK"));
            }
            throw $e;
        } finally 
        {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

}
