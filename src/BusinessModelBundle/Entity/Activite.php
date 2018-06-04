<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table()
*
@ORM\Entity(repositoryClass="BusinessModelBundle\Entity\ActiviteRepository")
*/
class Activite
{
/**
* @var integer $id
*
* @ORM\Column(name="id", type="integer")
* @ORM\Id
* @ORM\GeneratedValue(strategy="AUTO")
*/
private $id;
/**
* @var string $libelle
*
* @ORM\Column(name="libelle", type="string", length=255)
*/
private $libelle;
/**
* @var string $lieuDestination
*
* @ORM\Column(name="lieuDestination", type="string", length=255)
*/
private $lieuDestination;
/**
* @var date $date
*
* @ORM\Column(name="date", type="date")
*/
private $date;
/**
* @var time $heure
*
* @ORM\Column(name="heure", type="time")
*/
private $heure;
/**
* @var integer $nbParticipants
*
* @ORM\Column(name="nbParticipants", type="integer")
*/
private $nbParticipants;
/**
* @var float $prixIndividu
*
* @ORM\Column(name="prixIndividu", type="float")
*/
private $prixIndividu;
/**
* @var text $description
*
* @ORM\Column(name="description", type="text")
*/
private $description;
/**
* @return integer
*/
public function getId()
{
return $this->id;
}
/**
* @param string $lieuDestination
* @return Activite
*/
public function setLieuDestination($lieuDestination)
{
$this->lieuDestination = $lieuDestination;
return $this;
}
/**
* @return string
*/
public function getLieuDestination()
{
return $this->lieuDestination;
}
/**
* @param date $date
* @return Activite
*/
public function setDate($date)
{
$this->date = $date;
return $this;
}
/**
* @return date
*/
public function getDate()
{
return $this->date;
}
/**
* @param time $heure
* @return Activite
*/
public function setHeure($heure)
{
$this->heure = $heure;
return $this;
}
/**
* @return time
*/
public function getHeure()
{
return $this->heure;
}
/**
* @param integer $nbParticipants
* @return Activite
*/
public function setNbParticipants($nbParticipants)
{
$this->nbParticipants = $nbParticipants;
return $this;
}
/**
* @return integer
*/
public function getNbParticipants()
{
return $this->nbParticipants;
}
/**
* @param double $prixIndividu
* @return Activite
*/
public function setPrixIndividu($prixIndividu)
{
$this->prixIndividu = $prixIndividu;
return $this;
}
/**
* @return double
*/
public function getPrixIndividu()
{
return $this->prixIndividu;
}
/**
* @param text $description
* @return Activite
*/
public function setDescription($description)
{
$this->description = $description;
return $this;
}
/**
* @return text
*/
public function getDescription()
{
return $this->description;
}


    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Activite
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
}