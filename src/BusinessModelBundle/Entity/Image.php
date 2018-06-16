<?php
		
namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
		
		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\ImageRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Image extends ClasseMere
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
		* @var string $alt
		*
		* @ORM\Column(name="alt", type="string", length=255)
		*/
		private $alt;
		
		
		/**
		     * @var string $url
		     *
		     * @ORM\Column(name="url", type="string", length=255)
		   */
		private $url;
		
		
		/**
		* @ORM\ManyToOne(targetEntity="BusinessModelBundle\Entity\Activite",inversedBy="images")
		* @ORM\JoinColumn(nullable=true)
		*/
		private $activite;
		    
		
		private $fichier;
		
		
		private $temp;
		
		
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

		
		//Methodes permettant de gerer nos uploads pour les images des utilisateurs
	
	
		public function setFichier(UploadedFile $fichier=null)
		{
			
		$this->fichier = $fichier;
		if (null !== $this->alt) {
		// On sauvegarde l'extension du fichier pour le supprimer plus tard
		$this->temp = $this->alt;
		$this->alt = null;
		}
		}
		
		public function getFichier()
		{
		return $this->fichier;
		}
	
	
		/**
		 * @ORM\PrePersist()
		 * @ORM\PreUpdate()
		*/
		public function preUpload()
		{
		// Si jamais il n'y a pas de fichier (champ facultatif)
		if (null === $this->fichier) {
		return;
		}
		// Le nom du fichier est son nom avec son extension
		$this->alt = $this->fichier->getClientOriginalName();
	   $this->url = $this->getUploadDir()."/".$this->fichier->getClientOriginalName();
		}
	
		/**
		 * @ORM\PostPersist()
		 * @ORM\PostUpdate()
		*/
		public function upload()
		{
		// Si jamais il n'y a pas de fichier (champ facultatif)
		if (null == $this->fichier) {
		return;
		}
		// Si on avait un ancien fichier, on le supprime
		if (null != $this->temp) {
		$oldFile = $this->getUploadRootDir().'/'.$this->alt;
		if (file_exists($oldFile)) {
		unlink($oldFile);
		}
		}
		// On déplace le fichier envoyé dans le répertoire de notre choix
		try{
		$this->fichier->move($this->getUploadRootDir(), $this->alt);
		}catch(\Exception $e){}
		}
			
		/**
		 * @ORM\PreRemove()
		*/
		public function preRemoveUpload()
		{
		// On sauvegarde temporairement le nom du fichier
		if($this->alt!=null)
		$this->temp = $this->getUploadRootDir().'/'.$this->alt;
		}
		
		/**
		 * @ORM\PostRemove()
		*/
		public function removeUpload()
		{
		// En PostRemove, on utilise notre nom sauvegardé
		if ($this->temp!=null && file_exists($this->temp)) {
		// On supprime le fichier
		unlink($this->temp);
		}
		}
		public function getUploadDir()
		{
		// On retourne le chemin relatif du dossier des images uploadees
		return 'upload_images/galerie/'
		.$this->getDateCreation()->format('Y')."/"
		.$this->getDateCreation()->format('m')."/"
		.$this->getDateCreation()->format('d')."/"
		.$this->getDateCreation()->format('H')."/"
		.$this->getDateCreation()->format('i');
		}
		protected function getUploadRootDir()
		{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		return __DIR__.'/../../../web/'.$this->getUploadDir();
		}


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Image
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
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return Image
     */
    public function setActivite(\BusinessModelBundle\Entity\Activite $activite)
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

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Image
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
     * @return Image
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
