<?php 

namespace App\Models;

class Commande 
{
    private $id_commande; 
    private $date; 
    private $quantite; 
    private $id_utilisateur;

    public function getId_commande()
    {
        return $this->id_commande;
    }

    public function setId_commande($id_commande)
    {
        $this->id_commande = $id_commande;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

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
}
