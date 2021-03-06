<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups

		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\PageRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Page extends ClasseMere
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
		* @var string $titrePage
		*
		* @ORM\Column(name="titrePage", type="string", length=255)
		* @Assert\Length(
      *      min = 3
      * )
		*/
		private $titrePage;
		
		
		/**
		* @var text $contenu
		*
		* @ORM\Column(name="contenu", type="text")
		* @Assert\NotBlank()
		*/
		private $contenu;
		
		
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
		* @param string $titrePage
		* @return Page
		*/
		public function setTitrePage($titrePage)
		{
		$this->titrePage = $titrePage;
		return $this;
		}
		
		
		/**
		* @return string
		*/
		public function getTitrePage()
		{
		return $this->titrePage;
		}
		
		
		/**
		* @param text $contenu
		* @return Page
		*/
		public function setContenu($contenu)
		{
		$this->contenu = $contenu;
		return $this;
		}
		
		
		/**
		* @return text
		*/
		public function getContenu()
		{
		return $this->contenu;
		}

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Page
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
     * @return Page
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
