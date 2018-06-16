<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\DiscussionRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Discussion extends ClasseMere
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
		* @var string $message
		*
		* @ORM\Column(name="message", type="text")
		*/
		private $message;
		
		
		/**
		 * @ORM\ManyToMany(targetEntity="BusinessModelBundle\Entity\User")
		 */
		private $destinataires;
		
		
		/**
		* @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\User",inversedBy="discussions")
		* @ORM\JoinColumn(nullable=false)
		*/
		private $auteur;
		
		
		/**
		* @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\Activite",inversedBy="discussions")
		* @ORM\JoinColumn(nullable=false)
		*/
		private $activite;
		
		
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
     * Constructor
     */
    public function __construct()
    {
        $this->destinataires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Discussion
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Discussion
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
     * @return Discussion
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
     * Add destinataire
     *
     * @param \BusinessModelBundle\Entity\User $destinataire
     *
     * @return Discussion
     */
    public function addDestinataire(\BusinessModelBundle\Entity\User $destinataire)
    {
        $this->destinataires[] = $destinataire;

        return $this;
    }

    /**
     * Remove destinataire
     *
     * @param \BusinessModelBundle\Entity\User $destinataire
     */
    public function removeDestinataire(\BusinessModelBundle\Entity\User $destinataire)
    {
        $this->destinataires->removeElement($destinataire);
    }

    /**
     * Get destinataires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDestinataires()
    {
        return $this->destinataires;
    }

    /**
     * Set auteur
     *
     * @param \BusinessModelBundle\Entity\User $auteur
     *
     * @return Discussion
     */
    public function setAuteur(\BusinessModelBundle\Entity\User $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \BusinessModelBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return Discussion
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
