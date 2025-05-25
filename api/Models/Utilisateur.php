<?php 

namespace App\Models; 

class Utilisateur 
{
    private $id_utilisateur; 
    private $nom; 
    private $prenom; 
    private $email; 
    private $mot_passe; 
    private $role;

    public function getId_utilisateur()
    {
        return $this->id_utilisateur;
    }

    public function setId_utilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }
 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getMot_passe()
    {
        return $this->mot_passe;
    }

    public function setMot_passe($mot_passe)
    {
        $this->mot_passe = $mot_passe;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}
