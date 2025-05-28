<?php 

namespace App\Models; 

class CommandeRepas 
{
    private $id_commande_repas; 
    private $id_repas; 
    private $id_commande; 
    private $quantite; 

    public function getId_commande_repas()
    {
        return $this->id_commande_repas;
    }

    public function setId_commande_repas($id_commande_repas)
    {
        $this->id_commande_repas = $id_commande_repas;

        return $this;
    }

    public function getId_repas()
    {
        return $this->id_repas;
    }

    public function setId_repas($id_repas)
    {
        $this->id_repas = $id_repas;

        return $this;
    }

    public function getId_commande()
    {
        return $this->id_commande;
    }

    public function setId_commande($id_commande)
    {
        $this->id_commande = $id_commande;

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
}
