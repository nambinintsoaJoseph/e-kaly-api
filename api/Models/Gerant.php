<?php 

namespace App\Models; 
use App\Models\Utilisateur; 

class Gerant extends Utilisateur
{
    private $date_nomination; 
 
    public function getDate_nomination()
    {
        return $this->date_nomination;
    }

    public function setDate_nomination($date_nomination)
    {
        $this->date_nomination = $date_nomination;

        return $this;
    }
}
