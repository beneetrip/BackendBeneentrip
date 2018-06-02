<?php

namespace AdminBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AdminBundle\Entity\UserRepository")
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
    */
    protected $genre;
    
    /**
    * @var string $nomComplet
    *
    * @ORM\Column(name="nomcomplet", type="string", length=255)
    */
    protected $nomComplet;
        
    /**
    * @var integer $age
    *
    * @ORM\Column(name="age", type="integer")
    */
    protected $age;
    
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
    */
    protected $typeUtilisateur;
    
    
    protected $fichierPhoto;
    
    protected $tempPhoto;
    

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set nomComplet
     *
     * @param string $nomComplet
    */
	public function setNomComplet($nomComplet)
	{
	$this->nomComplet = $nomComplet;
	}
	/**
	* Get nomComplet
	*
	* @return string
	*/
	public function getNomComplet()
	{
	return $this->nomComplet;
	}
	
	
	/**
     * Set age
     *
     * @param integer $age
    */
	public function setAge($age)
	{
	$this->age = $age;
	}
	/**
	* Get age
	*
	* @return integer
	*/
	public function getAge()
	{
	return $this->age;
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
	if (null === $this->fichierPhoto) {
	return;
	}
	// Le nom du fichier est son nom avec son extension
	$this->photo = $this->fichierPhoto->getClientOriginalName();
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
	$oldFile = $this->getUploadRootDir().'/'.$this->photo;
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
	return 'bundles/admin/upload_images';
	}
	protected function getUploadRootDir()
	{
	// On retourne le chemin relatif vers l'image pour notre code PHP
	return __DIR__.'/../../../web/'.$this->getUploadDir();
	}

}
