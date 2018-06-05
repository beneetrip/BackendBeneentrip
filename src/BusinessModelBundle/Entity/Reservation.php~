<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Table()
* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\ReservationRepository")
* @ORM\HasLifecycleCallbacks
*/
class Reservation
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
* @var datetime $dateCreation
*
* @ORM\Column(name="dateCreation", type="datetime")
*/
private $dateCreation;
/**
* @var datetime $dateModification
*
* @ORM\Column(name="dateModification", type="datetime", nullable=true)
*/
private $dateModification;
/**
 * @ORM\ManyToMany(targetEntity="BusinessModelBundle\Entity\User",cascade={"persist"})
 */
private $utilisateurs;

/**
 * @ORM\OneToOne(targetEntity="BusinessModelBundle\Entity\Activite",cascade={"persist", "remove"})
 */
private $activite;

public function __construct()
{
$this->dateCreation= new \Datetime;
//$this->utilisateurs= new \Doctrine\Common\Collections\ArrayCollection();
}
/**
 * @ORM\PreUpdate()
 */
public function updateDate()
{
$this->setDateModification(new \Datetime());
}
/**
* @return integer
*/
public function getId()
{
return $this->id;
}

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Reservation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Reservation
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Add utilisateur
     *
     * @param \BusinessModelBundle\Entity\User $utilisateur
     *
     * @return Reservation
     */
    public function addUtilisateur(\BusinessModelBundle\Entity\User $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \BusinessModelBundle\Entity\User $utilisateur
     */
    public function removeUtilisateur(\BusinessModelBundle\Entity\User $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Set activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return Reservation
     */
    public function setActivite(\BusinessModelBundle\Entity\Activite $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return \BusinessModelBundle\Entity\Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }
}
