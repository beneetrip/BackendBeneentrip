<?php

namespace BusinessModelBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

	/**
	 * @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\UserRepository")
	 * @ORM\Table(name="utilisateurs")
	 * @ORM\HasLifecycleCallbacks
	 */
	class User extends BaseUser
	{
    
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

   
   /**
    * @var string $genre
    *
    * @ORM\Column(name="genre", type="string", length=255)
    * @Assert\Choice({"Homme", "Femme"})
    */
    protected $genre;
    
    
    /**
    * @var string $nom
    *
    * @ORM\Column(name="nom", type="string", length=255)
    * @Assert\Length(
    *      min = 3
    * )
    */
    protected $nom;
    
    /**
    * @var string $prenom
    *
    * @ORM\Column(name="prenom", type="string", length=255)
    * @Assert\Length(
    *      min = 3
    * )
    */
    protected $prenom;
        
    
	 /**
		* @var date $dateNaissance
		*
		* @ORM\Column(name="dateNaissance", type="date")
		* @Assert\LessThan("-17 years")
		*/
		private $dateNaissance;    
    
	   /**
	   * @ORM\Column(type="string", length=50, nullable=true)
	   * @AssertPhoneNumber(type="mobile")
	   */
	    protected $telephone;
    
    
    /**
    * @var string $photo
    *
    * @ORM\Column(name="photo", type="string", length=255, nullable=true)
    */
    protected $photo;
    
    
    /**
    * @var string $typeUtilisateur
    *
    * @ORM\Column(name="typeutilisateur", type="string", length=255)
    * @Assert\Choice({"Guide", "Touriste"})
    */
    protected $typeUtilisateur;
    
    
    /**
    * @var string $privilege
    *
    * @ORM\Column(name="privilege", type="string", length=255)
    * @Assert\Choice({"ROLE_USER", "ROLE_ADMIN"})
    */
    protected $privilege;
    
    
    /**
     * @var string $urlPhoto
     *
     * @ORM\Column(name="urlPhoto", type="string", length=255, nullable=true)
   */
    protected $urlPhoto;
    
    
	  /**
		 * @ORM\OneToOne(targetEntity="BusinessModelBundle\Entity\Image", cascade={"persist", "remove"})
		 * @ORM\JoinColumn(nullable=true)
		 */
		private $avatar;  

	  
	  /**
		* @var datetime $dateCreation
		*
		* @ORM\Column(name="dateCreation", type="datetime")
		*/
		protected $dateCreation;
	  
	  
	  /**
		* @var datetime $dateModification
		*
		* @ORM\Column(name="dateModification", type="datetime", nullable=true)
		*/
		protected $dateModification;
		
		
		/**
        * @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Discussion",cascade={"persist", "remove"}, mappedBy="auteur")
        */
      protected $discussions;
      
      
      /**
        * @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Activite",cascade={"persist", "remove"}, mappedBy="auteur")
        */
      protected $activites;
      
      
      /**
        * @ORM\OneToMany(targetEntity="BusinessModelBundle\Entity\Payment",cascade={"persist", "remove"}, mappedBy="utilisateur")
        */
      protected $payments;
      
      /**
		 * @ORM\ManyToMany(targetEntity="BusinessModelBundle\Entity\Langue")
		 * @Assert\NotNull()
		 */
		private $langues;
    
    
    
    /**
     * @Assert\File(
     *     maxSize = "6M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"}
     * )
     */
      protected $fichierPhoto;
    
    
    
      protected $tempPhoto;
      
      
		/**
    	* @var string $monnaie
    	*
    	* @ORM\Column(name="monnaie", type="string", length=255, options={"default":"EUR"}, nullable=true)
    	*/
      protected $monnaie;
      

	    public function __construct()
	    {
	        parent::__construct();
	        // your own logic
	    }
	
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
	     * Set genre
	     *
	     * @param string $genre
	    */
		public function setGenre($genre)
		{
		$this->genre = $genre;
		}
		/**
		* Get genre
		*
		* @return string
		*/
		public function getGenre()
		{
		return $this->genre;
		}
		
		
		/**
	     * Set photo
	     *
	     * @param string $photo
	    */
		public function setPhoto($photo)
		{
		$this->photo = $photo;
		}
		/**
		* Get photo
		*
		* @return string
		*/
		public function getPhoto()
		{
		return $this->photo;
		}
		
	
		/**
	     * Set typeUtilisateur
	     *
	     * @param string $typeUtilisateur
	    */
		public function setTypeUtilisateur($typeUtilisateur)
		{
		$this->typeUtilisateur = $typeUtilisateur;
		}
		/**
		* Get typeUtilisateur
		*
		* @return string
		*/
		public function getTypeUtilisateur()
		{
		return $this->typeUtilisateur;
		}
		
		
		
	//Methodes permettant de gerer nos uploads pour les images des utilisateurs
	
	
		public function setFichierPhoto(UploadedFile $fichierPhoto=null)
		{
			
		$this->fichierPhoto = $fichierPhoto;
		//var_dump($fichierPhoto);
		//print_r($fichierPhoto);
		// On vérifie si on avait déjà un fichier pour cette entité
		if (null !== $this->photo) {
		// On sauvegarde l'extension du fichier pour le supprimer plus tard
		$this->tempPhoto = $this->photo;
		// On réinitialise les valeurs des attributs url et alt
		$this->photo = null;
		}
		}
		
		public function getFichierPhoto()
		{
		return $this->fichierPhoto;
		}
	
	
		/**
		 * @ORM\PrePersist()
		 * @ORM\PreUpdate()
		*/
		public function preUpload()
		{
		// Si jamais il n'y a pas de fichier (champ facultatif)
		if (null == $this->fichierPhoto) {
		return;
		}
		// Le nom du fichier est son nom avec son extension
		$this->photo = $this->fichierPhoto->getClientOriginalName();
	   $this->urlPhoto = $this->getUploadDir()."/".$this->fichierPhoto->getClientOriginalName();
	   //On teste si l'id est non null alors on utilise l'id pour le nom de photo sinon on reste  avec le nom original de la photo
	   if($this->getId()!=null){
	   //Desormais les photos des utilisateurs seront sauvegardees avec les id des utilisateurs
		$this->photo = $this->getId().".".$this->fichierPhoto->getClientOriginalExtension();
	   $this->urlPhoto = $this->getUploadDir()."/".$this->photo;
	   }
		}
	
		/**
		 * @ORM\PostPersist()
		 * @ORM\PostUpdate()
		*/
		public function upload()
		{
		// Si jamais il n'y a pas de fichier (champ facultatif)
		if (null == $this->fichierPhoto) {
		return;
		}
		// Si on avait un ancien fichier, on le supprime
		if (null != $this->tempPhoto) {
		$oldFile = $this->getUploadRootDir().'/'.$this->tempPhoto;
		if (file_exists($oldFile)) {
		unlink($oldFile);
		}
		}
		// On déplace le fichier envoyé dans le répertoire de notre choix
		try{
		$this->fichierPhoto->move($this->getUploadRootDir(), $this->photo);
		}catch(\Exception $e){}	
		}
		
		
		/**
		 * @ORM\PreRemove()
		*/
		public function preRemoveUpload()
		{
		// On sauvegarde temporairement le nom du fichier
		if($this->photo!=null)
		$this->tempPhoto = $this->getUploadRootDir().'/'.$this->photo;
		}
		
		/**
		 * @ORM\PostRemove()
		*/
		public function removeUpload()
		{
		// En PostRemove, on utilise notre nom sauvegardé
		if ($this->tempPhoto!=null && file_exists($this->tempPhoto)) {
		// On supprime le fichier
		unlink($this->tempPhoto);
		}
		}
		public function getUploadDir()
		{
		// On retourne le chemin relatif du dossier des images uploadees
		return 'upload_images/users';
		}		
		
		protected function getUploadRootDir()
		{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		return __DIR__.'/../../../web/'.$this->getUploadDir();
		}
	
	    /**
	     * Set urlPhoto
	     *
	     * @param string $urlPhoto
	     *
	     * @return User
	     */
	    public function setUrlPhoto($urlPhoto)
	    {
	        $this->urlPhoto = $urlPhoto;
	
	        return $this;
	    }
	
	    /**
	     * Get urlPhoto
	     *
	     * @return string
	     */
	    public function getUrlPhoto()
	    {
	        return $this->urlPhoto;
	    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return User
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
     * @return User
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
     * @return User
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
     * Set privilege
     *
     * @param string $privilege
     *
     * @return User
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;

        return $this;
    }

    /**
     * Get privilege
     *
     * @return string
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * Add activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return User
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
    
  
    /**
     * Add langue
     *
     * @param \BusinessModelBundle\Entity\Langue $langue
     *
     * @return User
     */
    public function addLangue(\BusinessModelBundle\Entity\Langue $langue)
    {
        $this->langues[] = $langue;

        return $this;
    }

    /**
     * Remove langue
     *
     * @param \BusinessModelBundle\Entity\Langue $langue
     */
    public function removeLangue(\BusinessModelBundle\Entity\Langue $langue)
    {
        $this->langues->removeElement($langue);
    }

    /**
     * Get langues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLangues()
    {
        return $this->langues;
    }
    
    
    //fonction permettant d'hydrater cad remplir un objet de la classe a partir des donnees d'un tableau
	 public function hydrate(array $Tabdonnees){

		//on parcourt notre Tableau bidimensionnel cle-valeur cad qu'on a un tableau de donnees tel que Tabdonnees[key]=value 
		foreach ($Tabdonnees as $key => $value){
			// On récupère le nom du mutateur correspondant à l'attribut: Les methodes ont pour nom setNomdeLattribut(avec la premiere lettre de l'attribut en majuscule)
			$method = 'set'.ucfirst($key);

			// Si le mutateur correspondant existe.
			if (method_exists($this, $method))
			{
			// On appelle le mutateur.
			$this->$method($value);
			}
			
		}

	}
	
	//Fonction speciale pour retourner le nom complet de l'utlisateur dans un dropdown
	public function getNomComplet()
	{
    return $this->prenom.'  '.$this->nom;
	}
	
	//Fonction speciale pour retourner l'age de l'utlisateur
	public function getAge()
	{
	 $datetime1 = new \DateTime();                // date actuelle
    $datetime2 = new \DateTime(date_format($this->getDateNaissance(),'Y-m-d'));
    $interval = $datetime1->diff($datetime2);	
    //var_dump($interval);
    return $interval->y;
	}
	
		

    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Add payments
     *
     * @param \BusinessModelBundle\Entity\Payment $payments
     * @return User
     */
    public function addPayment(\BusinessModelBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \BusinessModelBundle\Entity\Payment $payments
     */
    public function removePayment(\BusinessModelBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set avatar
     *
     * @param \BusinessModelBundle\Entity\Image $avatar
     * @return User
     */
    public function setAvatar(\BusinessModelBundle\Entity\Image $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \BusinessModelBundle\Entity\Image 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set monnaie
     *
     * @param string $monnaie
     * @return User
     */
    public function setMonnaie($monnaie)
    {
        $this->monnaie = $monnaie;

        return $this;
    }

    /**
     * Get monnaie
     *
     * @return string 
     */
    public function getMonnaie()
    {
        return $this->monnaie;
    }
}
