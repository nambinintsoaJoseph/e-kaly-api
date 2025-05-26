<?php 

namespace App\Models; 

class Repas 
{
    private $id_repas; 
    private $id_utilisateur; 
    private $nom; 
    private $description;
    private $photo; 
    private $prix; 

    public function getId_repas()
    {
        return $this->id_repas;
    }

    public function setId_repas($id_repas)
    {
        $this->id_repas = $id_repas;

        return $this;
    }

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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }
}
