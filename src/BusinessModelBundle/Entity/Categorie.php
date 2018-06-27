<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups

		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\CategorieRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Categorie extends ClasseMere
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
		* @var string $nom
		*
		* @ORM\Column(name="nom", type="string", length=255)
		* @Assert\Length(
      *      min = 3
      * )
		*/
		private $nom;
		
		
		/**
        * @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Activite",cascade={"persist", "remove"}, mappedBy="categorie")
        */
      protected $activites;		
		
		
		/**
		 * @ORM\PrePersist()
		 */
		public function createDate()
		{
		$this->setDateCreation(new \Datetime());
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
		* @param string $nom
		* @return Categorie
		*/
		public function setNom($nom)
		{
		$this->nom = $nom;
		return $this;
		}
		
		
		/**
		* @return string
		*/
		public function getNom()
		{
		return $this->nom;
		}

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Categorie
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
     * @return Categorie
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
     * Constructor
     */
    public function __construct()
    {
        $this->activites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return Categorie
     */
    public function addActivite(\BusinessModelBundle\Entity\Activite $activite)
    {
        $this->activites[] = $activite;

        return $this;
    }

    /**
     * Remove activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     */
    public function removeActivite(\BusinessModelBundle\Entity\Activite $activite)
    {
        $this->activites->removeElement($activite);
    }

    /**
     * Get activites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivites()
    {
        return $this->activites;
    }
}
