<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups

		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\PaymentRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Payment extends ClasseMere
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
		* @var string $ref
		*
		* @ORM\Column(name="ref", type="string", length=255, nullable=true)
		*/
		private $ref;
		
		
		
		/**
		* @var string $transactionId
		*
		* @ORM\Column(name="transactionId", type="string", length=255, nullable=true)
		*/
		private $transactionId;
		
		
		/**
		* @var float $amount
		*
		* @ORM\Column(name="amount", type="float")
		* @Assert\GreaterThanOrEqual(0)
		*/
		private $amount;
			
		
		/**
		* @var string $currencyCode
		*
		* @ORM\Column(name="currencyCode", type="string", length=5)
		*/
		private $currencyCode;
		
		
		/**
		* @var string $status
		*
		* @ORM\Column(name="status", type="string", length=255, options={"default":"UNCOMPLETED"})
		*/
		private $status="UNCOMPLETED";
		
		
		/**
		* @var string $transactionToken
		*
		* @ORM\Column(name="transactionToken", type="string", length=255, nullable=true)
		*/
		private $transactionToken;
		
		
		/**
		* @var string $transactionPayer
		*
		* @ORM\Column(name="transactionPayer", type="string", length=255, nullable=true)
		*/
		private $transactionPayer;
		
		/**
       * @var string $invoice
       *
       * @ORM\Column(name="invoice", type="string", length=255, nullable=true)
       */
      private $invoice;
		
		
		/**
		 * @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\User",inversedBy="payments")
		 * @ORM\JoinColumn(nullable=false)
		 */
		private $utilisateur;
		
		
		/**
		 * @ORM\OneToOne(targetEntity="BusinessModelBundle\Entity\Reservation", cascade={"persist", "remove"})
		 * @ORM\JoinColumn(nullable=false)
		 */
		private $reservation;
		
		
		/**
		 * @ORM\PrePersist()
		 */
		public function createDate()
		{
		$nowDate=new \DateTime();
		$this->setDateCreation($nowDate);
	   $this->setRef(''.$nowDate->format('dmYHis'));	
		}
		
		
		/**
		 * @ORM\PreUpdate()
		 */
		public function updateDate()
		{
		$nowDate=new \DateTime();	
		$this->setDateModification($nowDate);
	   $this->setRef(''.$nowDate->format('dmYHis'));	
		}
		

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return Payment
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     * @return Payment
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string 
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Payment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set transactionToken
     *
     * @param string $transactionToken
     * @return Payment
     */
    public function setTransactionToken($transactionToken)
    {
        $this->transactionToken = $transactionToken;

        return $this;
    }

    /**
     * Get transactionToken
     *
     * @return string 
     */
    public function getTransactionToken()
    {
        return $this->transactionToken;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Payment
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
     * @return Payment
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
     * Set utilisateur
     *
     * @param \BusinessModelBundle\Entity\User $utilisateur
     * @return Payment
     */
    public function setUtilisateur(\BusinessModelBundle\Entity\User $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \BusinessModelBundle\Entity\User 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set invoice
     *
     * @param string $invoice
     * @return Payment
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return string 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set transactionPayer
     *
     * @param string $transactionPayer
     * @return Payment
     */
    public function setTransactionPayer($transactionPayer)
    {
        $this->transactionPayer = $transactionPayer;

        return $this;
    }

    /**
     * Get transactionPayer
     *
     * @return string 
     */
    public function getTransactionPayer()
    {
        return $this->transactionPayer;
    }

    /**
     * Set reservation
     *
     * @param \BusinessModelBundle\Entity\Reservation $reservation
     * @return Payment
     */
    public function setReservation(\BusinessModelBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \BusinessModelBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Set ref
     *
     * @param string $ref
     * @return Payment
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }
}
