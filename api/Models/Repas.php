<?php 

namespace App\Models; 

class Repas 
{
    private $id_repas; 
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
