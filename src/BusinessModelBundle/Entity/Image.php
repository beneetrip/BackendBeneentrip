<?php
		
namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;//for validation groups voir http://symfony.com/doc/current/validation.html#validation-groups
		
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
		* @Assert\Length(
      *      min = 3
      * )
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
		    
		
		
	/**
     * @Assert\File(
     *     maxSize = "2M",
     *     mimeTypes = {"image/png", "image/jpeg", "image/jpg"}
     * )
     * @Assert\NotNull()
     */
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
		$oldFile = $this->getUploadRootDir().'/'.$this->temp;
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
    
    //Cette fonction permet de renvoyer un tableau de dimension de l'image qu'on soit modifie
    public function modifierImage($img, $largeur, $hauteur) {
    $dst_w = $largeur;
    $dst_h = $hauteur;
     
     // Lit les dimensions de l'image
   $src_w = imagesx($img);
   $src_h = imagesy($img);
    
   // Teste les dimensions tenant dans la zone
   $test_h = round(($dst_w / $src_w) * $src_h);
   $test_w = round(($dst_h / $src_h) * $src_w);
    
   // Si Height final non précisé (0)
   if(!$dst_h) $dst_h = $test_h;
    
   // Sinon si Width final non précisé (0)
   elseif(!$dst_w) $dst_w = $test_w;
    
   // Sinon teste quel redimensionnement tient dans la zone
   elseif($test_h>$dst_h) $dst_w = $test_w;
   else $dst_h = $test_h;
    
    if($dst_h > 1 && $dst_h < $hauteur){
        $paddingTop = ceil(($hauteur - $dst_h) / 2);
    }
    else{
        $paddingTop = 0;
    }
    $pad = " style=\"margin-top:".$paddingTop."px;\"";
    
    $tab = array($dst_w, $dst_h, $pad);
    return $tab;
	}
	
	//Fonction permettant d'obtenir l'extension a partir du nom du fichier image
	public function getExtension($nomfichier){
	$partieFichier = explode(".",$nomfichier);
	if(count($partieFichier) > 1){
	$extension = array_pop($partieFichier);
	if($extension=="jpg")
	$extension="jpeg";
	}
	else
	$extension = null;
	
	return $extension;	
	}
	
	//Fonction permettant d'obtenir l'extension a partir du nom du fichier image
	public function getNomSansExtension($nomfichier){
	$partieFichier = explode(".",$nomfichier);
	if(count($partieFichier) > 1){
	$extension = array_pop($partieFichier);
	if($extension=="jpg")
	$extension="jpeg";
	}
	else
	$extension = null;
	
	return implode(".",$partieFichier);	
	}
		
	
	//Fonction permettant de creer des thumbs de largeur x hauteur comme on veut, le thumb est gardee dans le meme dossier que l'image
	//Elle est generique et travaille juste avec l'url de l'image pour avoir tous les infos pour creer le thumb de l'image en question
	public function createThumb($largeur, $hauteur){
	
	//On recupere les infos dont on a besoin de l'url de l'image : le nom de l'image et son dossier

	$tab = explode("/",$this->getUrl());	
	
	$nomImage= array_pop($tab);
	
	$dossierRelatif= implode("/",$tab);	
	
	$extension=$this->getExtension($nomImage);	
	if($extension==null)
	return ;
	
	$dir=__DIR__.'/../../../web/'.$dossierRelatif;
	
	$nomThumb=$dir.'/'.$this->getNomSansExtension($nomImage).'_Thumb'.$largeur.'X'.$hauteur.'.'.$extension;	
	
	if (file_exists($nomThumb))
	return ;	
	
	$method1="imagecreatefrom".strtolower($extension);	
	
	$img_src = $method1($dir.'/'.$nomImage);
 
	$dimension = $this->modifierImage($img_src, $largeur, $hauteur);
         
	$nameTemp="temp.".$extension; 	
	
			// Redimensionner
        $thumb = imagecreatetruecolor($dimension[0], $dimension[1]) or die('Impossible de creer l\'image de destination pour la miniature');
         
        // Les fonctions imagesx et imagesy renvoient la largeur et la hauteur d'une image
        $largeur_source = imagesx($img_src);
        $hauteur_source = imagesy($img_src);
        $largeur_thumb = imagesx($thumb);
        $hauteur_thumb = imagesy($thumb);
         
        // On crée la miniature
        imagecopyresampled($thumb, $img_src, 0, 0, 0, 0, $largeur_thumb, $hauteur_thumb, $largeur_source, $hauteur_source);
		 //Puis j'enregistre la miniature, sinon, la suite ne marche pas. j'ai fait trop d'essais
		 $method2="image".strtolower($extension);	
       $method2($thumb, $dir.'/'.$nameTemp);
       
       // =================================
		// Melange des images
		// ================================
         
        // Ouvertrue de la source redimensionnée et enregistree tout à l'heure
         
        $new_img_src = $method1($dir.'/'.$nameTemp);
         
         
        // Création de l'image de destination
         
        $fond = imagecreatetruecolor($largeur, $hauteur);
        imagecolorallocate($fond, 0, 0, 0);
         
        // Récuperation des nouvelles dimensions
         
        $fond_w = imagesx($fond);
        $fond_h = imagesy($fond);
         
        $sample_w = imagesx($new_img_src);
        $sample_h = imagesy($new_img_src);
         
        imagecopymerge($fond, $new_img_src, ($fond_w - $sample_w) /2, ($fond_h - $sample_h) / 2, 0, 0, $fond_w, $fond_h, 100) or die('melange impossible');
        // On enregistre la nouvelle image "pellicule" sous un nouveau nom
        $method2($fond,$nomThumb);
			// Pour finir je supprime la première image que j'ai enregistré.
        unlink($dir.'/'.$nameTemp);
	}

	public function linkThumb($largeur, $hauteur){
		
		//On recupere les infos dont on a besoin de l'url de l'image : le nom de l'image et son dossier

		$tab = explode("/",$this->getUrl());	
		
		$nomImage= array_pop($tab);
		
		$dossierRelatif= implode("/",$tab);	
		
		$extension=$this->getExtension($nomImage);	
		if($extension==null)
		return ;
		
		$dir = __DIR__.'/../../../web/'.$dossierRelatif;
		
		$nomThumb = $dir.'/'.$this->getNomSansExtension($nomImage).'_Thumb'.$largeur.'X'.$hauteur.'.'.$extension;
		
		$dir=__DIR__.'/../../../web/'.$dossierRelatif;
	
		$nomThumb = $dir.'/'.$this->getNomSansExtension($nomImage).'_Thumb'.$largeur.'X'.$hauteur.'.'.$extension;	
		
		$linkThumb = $dossierRelatif.'/'.$this->getNomSansExtension($nomImage).'_Thumb'.$largeur.'X'.$hauteur.'.'.$extension;	
		
		//si le fichier du thumb n'existe pas on le créer
		if (!file_exists($nomThumb))
		$this->createThumb($largeur, $hauteur);
	
		return $linkThumb;
		
	}
	
	
}
