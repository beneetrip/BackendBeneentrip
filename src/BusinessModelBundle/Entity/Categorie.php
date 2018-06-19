<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

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
		*/
		private $nom;
		
		
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
}
