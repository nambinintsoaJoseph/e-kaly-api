<?php 

namespace App\Models; 
use App\Models\Utilisateur;

class Agent extends Utilisateur
{
    private $service; 

    public function getService()
    {
        return $this->service;
    }

    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }
}
