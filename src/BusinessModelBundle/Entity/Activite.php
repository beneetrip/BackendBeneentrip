<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups

		/**
		* @ORM\Table()
		*@ORM\Entity(repositoryClass="BusinessModelBundle\Entity\ActiviteRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Activite extends ClasseMere
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
		* @Assert\Length(
      *      min = 3
      * )
		*/
		private $libelle;


		/**
		* @var string $lieuDestination
		*
		* @ORM\Column(name="lieuDestination", type="string", length=255)
		* @Assert\Length(
      *      min = 3
      * )
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
      * @Assert\GreaterThan(0)
		*/
		private $nbParticipants;


		/**
		* @var float $prixIndividu
		*
		* @ORM\Column(name="prixIndividu", type="float")
		* @Assert\GreaterThanOrEqual(0)
		*/
		private $prixIndividu;


		/**
		* @var text $description
		*
		* @ORM\Column(name="description", type="text")
		* @Assert\NotBlank()
		*/
		private $description;
		
		
		/**
		* @var text $nbVues
		*
		* @ORM\Column(name="nbVues", type="integer", options={"default":0})
		*/
		private $nbVues=0;

		
		/**
		* @var string $langueParlee
		*
		* @ORM\Column(name="langueParlee", type="string", length=255, nullable=true)
		*/
		private $langueParlee;


		/**
		 * @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\Categorie",inversedBy="activites")
		 * @ORM\JoinColumn(nullable=true)
		 */
		private $categorie;


		/**
		 * @ORM\OneToOne(targetEntity="BusinessModelBundle\Entity\Image", cascade={"persist", "remove"})
		 * @ORM\JoinColumn(nullable=false)
		 * @Assert\NotNull()
		 */
		private $imagePrincipale;


		/**
		 * @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Image",cascade={"persist", "remove"}, mappedBy="activite")
		 */
		private $images;
		

		/**
		* @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Discussion",cascade={"persist", "remove"}, mappedBy="activite")
		*/
		private $discussions;
		
		
		/**
		* @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\User",inversedBy="activites")
		* @ORM\JoinColumn(nullable=false)
		*/
		private $auteur;
		
		
		/**
    	* @var string $devise
    	*
    	* @ORM\Column(name="devise", type="string", length=255, options={"default":"EUR"}, nullable=true)
    	*/
      protected $devise;
      
      /**
		* @var integer $duree
		*
		* @ORM\Column(name="duree", type="integer", nullable=true)
      * @Assert\GreaterThan(0)
		*/
		private $duree;
		
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

    /**
     * Set categorie
     *
     * @param \BusinessModelBundle\Entity\Categorie $categorie
     *
     * @return Activite
     */
    public function setCategorie(\BusinessModelBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \BusinessModelBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add image
     *
     * @param \BusinessModelBundle\Entity\Image $image
     *
     * @return Activite
     */
    public function addImage(\BusinessModelBundle\Entity\Image $image)
    {
    	  //$image->setActivite($this);
        $this->images[] = $image;
        return $this;
    }

    /**
     * Remove image
     *
     * @param \BusinessModelBundle\Entity\Image $image
     */
    public function removeImage(\BusinessModelBundle\Entity\Image $image)
    {
    	  //$image->setActivite(null);//On ajoute ceci car on avait une jointure nullable=true
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Activite
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
     * @return Activite
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
     * Add discussion
     *
     * @param \BusinessModelBundle\Entity\Discussion $discussion
     *
     * @return Activite
     */
    public function addDiscussion(\BusinessModelBundle\Entity\Discussion $discussion)
    {
        $this->discussions[] = $discussion;

        return $this;
    }

    /**
     * Remove discussion
     *
     * @param \BusinessModelBundle\Entity\Discussion $discussion
     */
    public function removeDiscussion(\BusinessModelBundle\Entity\Discussion $discussion)
    {
        $this->discussions->removeElement($discussion);
    }

    /**
     * Get discussions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiscussions()
    {
        return $this->discussions;
    }

    /**
     * Set auteur
     *
     * @param \BusinessModelBundle\Entity\User $auteur
     *
     * @return Activite
     */
    public function setAuteur(\BusinessModelBundle\Entity\User $auteur)
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
     * Set imagePrincipale
     *
     * @param \BusinessModelBundle\Entity\Image $imagePrincipale
     *
     * @return Activite
     */
    public function setImagePrincipale(\BusinessModelBundle\Entity\Image $imagePrincipale)
    {
        $this->imagePrincipale = $imagePrincipale;

        return $this;
    }

    /**
     * Get imagePrincipale
     *
     * @return \BusinessModelBundle\Entity\Image
     */
    public function getImagePrincipale()
    {
        return $this->imagePrincipale;
    }

    /**
     * Add categorie
     *
     * @param \BusinessModelBundle\Entity\Categorie $categorie
     *
     * @return Activite
     */
    public function addCategorie(\BusinessModelBundle\Entity\Categorie $categorie)
    {
        $this->categorie[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \BusinessModelBundle\Entity\Categorie $categorie
     */
    public function removeCategorie(\BusinessModelBundle\Entity\Categorie $categorie)
    {
        $this->categorie->removeElement($categorie);
    }

    /**
     * Set nbVues
     *
     * @param integer $nbVues
     *
     * @return Activite
     */
    public function setNbVues($nbVues)
    {
        $this->nbVues = $nbVues;

        return $this;
    }

    /**
     * Get nbVues
     *
     * @return integer
     */
    public function getNbVues()
    {
        return $this->nbVues;
    }
    
    //fonction permettant de retourner la date de deroulement de l'Activite en clair
    public function getDateEnClair(){
    $formatter = new \IntlDateFormatter('fr_FR',\IntlDateFormatter::FULL,\IntlDateFormatter::NONE,'Europe/Paris',\IntlDateFormatter::GREGORIAN );
	 return $formatter->format(new \DateTime(date_format($this->getDate(),'Y/m/d')));
    }

    //fonction permettant de retourner la description de l'Activite sans balise HTML
    public function getDescriptionEnClair(){
	 return strip_tags($this->getDescription());
    }


    /**
     * Set langueParlee
     *
     * @param string $langueParlee
     *
     * @return Activite
     */
    public function setLangueParlee($langueParlee)
    {
        $this->langueParlee = $langueParlee;

        return $this;
    }

    /**
     * Get langueParlee
     *
     * @return string
     */
    public function getLangueParlee()
    {
        return $this->langueParlee;
    }

    /**
     * Set devise
     *
     * @param string $devise
     * @return Activite
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return string 
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     * @return Activite
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer 
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
