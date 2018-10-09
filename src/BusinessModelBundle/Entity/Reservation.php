<?php

namespace BusinessModelBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

		/**
		* @ORM\Table()
		* @ORM\Entity(repositoryClass="BusinessModelBundle\Entity\ReservationRepository")
		* @ORM\HasLifecycleCallbacks
		*/
		class Reservation extends ClasseMere
		{
		
		const TAX_RATE = "20.00";
		
		/**
		* @var integer $id
		*
		* @ORM\Column(name="id", type="integer")
		* @ORM\Id
		* @ORM\GeneratedValue(strategy="AUTO")
		*/
		private $id;
		
		
		/**
		 * @ORM\ManyToMany(targetEntity="BusinessModelBundle\Entity\User")
		 */
		private $utilisateurs;
		
		
		/**
		* @ORM\ManyToMany(targetEntity="BusinessModelBundle\Entity\Activite")
		*/
		private $activites;
		
		
		/**
       * @ORM\Column(name="paye", type="boolean", options={"default":false})
       */
      private $paye;	
      	
		
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
     * Add utilisateur
     *
     * @param \BusinessModelBundle\Entity\User $utilisateur
     *
     * @return Reservation
     */
    public function addUtilisateur(\BusinessModelBundle\Entity\User $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \BusinessModelBundle\Entity\User $utilisateur
     */
    public function removeUtilisateur(\BusinessModelBundle\Entity\User $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Reservation
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
     * @return Reservation
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
     * Set paye
     *
     * @param boolean $paye
     *
     * @return Reservation
     */
    public function setPaye($paye)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * Add activite
     *
     * @param \BusinessModelBundle\Entity\Activite $activite
     *
     * @return Reservation
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
    
    
    public function getBoutonPaiementPayPal(){
			
			// output: BackendBeneentrip/web/app_dev.php/....
			$currentPath = $_SERVER['PHP_SELF'];

			// output: localhost
			$hostName = $_SERVER['HTTP_HOST'];

			// output: http://
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
			
			$tab = explode("/",__DIR__);
						
			$nbelts=count($tab);
			
			$rootProject=$tab[$nbelts-4];
			
			if($rootProject!="www")
			$imgbouton=$protocol.$hostName."/".$rootProject.'/web/bundles/admin/img/BuyNow.png';
			else
			$imgbouton=$protocol.$hostName.'/web/bundles/admin/img/BuyNow.png';
			
			if($rootProject!="www")
			$url=$protocol.$hostName.'/'.$rootProject.'/web/app_dev.php/payment/end';
			else
			$url=$protocol.$hostName.'/web/payment/end';
			
			//$url=$protocol.$hostName.$currentPath;
			
			$amount=$this->calculerMontantTotal();
			$currencyCode='EUR';
			
			if($amount > 0.0){
			$paypal_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
			//$paypal_url='https://www.paypal.com/cgi-bin/webscr'; // Pro Paypal API URL
			}else{
			if($rootProject!="www")
			$paypal_url=$protocol.$hostName.'/'.$rootProject.'/web/app_dev.php/payment/validate';
			else
			$paypal_url=$protocol.$hostName.'/web/payment/validate';
			}
		
			$paypal_id='beneentrip@gmail.com'; // Business email ID		
			
			echo '<form action="'.$paypal_url.'" method="post" name="frmPayPal1">
			 <input type="hidden" name="business" value="'.$paypal_id.'">
			  <input type="hidden" name="cmd" value="_xclick"> 
			 <input type="hidden" name="item_name" value="Reservation_'.$this->getId().'">
			 <input type="hidden" name="item_number" value="'.$this->getId().'">
			 <input type="hidden" name="amount" value="'.$this->calculerMontantTotal().'">
			 <input type="hidden" name="tax_rate" value="'.self::TAX_RATE.'">
			 <input type="hidden" name="currency_code" value="'.$currencyCode.'">
			 <input type="hidden" name="custom" value="'.$this->getUtilisateurs()[0]->getId().'">
			 <input type="hidden" name="cancel_return" value="'.$url.'">
			 <input type="hidden" name="return" value="'.$url.'">
			<input type="image" src="'.$imgbouton.'" border="0" name="submit" 
			  alt="PayPal - The safer, easier way to pay online!">
			 </form>';  
    }
    
    public function getImageBoutonPaiementStripe(){
    // output: BackendBeneentrip/web/app_dev.php/....
			$currentPath = $_SERVER['PHP_SELF'];

			// output: localhost
			$hostName = $_SERVER['HTTP_HOST'];

			// output: http://
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
			
			$tab = explode("/",__DIR__);
						
			$nbelts=count($tab);
			
			$rootProject=$tab[$nbelts-4];
			
			if($rootProject!="www")
			$imgbouton=$protocol.$hostName."/".$rootProject.'/web/bundles/admin/img/BuyStripe.png';
			else
			$imgbouton=$protocol.$hostName.'/web/bundles/admin/img/BuyStripe.png';
			
			echo '<input type="image" src="'.$imgbouton.'" border="0" name="submit" 
			  alt="Stripe - The best way to accept payments online!">'; 
    }
    
    public function getBoutonPaiementStripe(){
   
    // output: BackendBeneentrip/web/app_dev.php/....
			$currentPath = $_SERVER['PHP_SELF'];

			// output: localhost
			$hostName = $_SERVER['HTTP_HOST'];

			// output: http://
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
			
			$tab = explode("/",__DIR__);
						
			$nbelts=count($tab);
			
			$rootProject=$tab[$nbelts-4];
			
			if($rootProject!="www")
			$imgbouton=$protocol.$hostName."/".$rootProject.'/web/bundles/admin/img/stripe.png';
			else
			$imgbouton=$protocol.$hostName.'/web/bundles/admin/img/stripe.png';
			
			
			
			//$url=$protocol.$hostName.$currentPath;		
		    $stripe_pkey='pk_test_JCG2KQDPdH54s7eJtwNjcQZm';
		    $amount=$this->calculerMontantTotal() * 100;
		    $currencyCode='EUR';
    		 
    		if($amount > 0.0){
			if($rootProject!="www")
			$url=$protocol.$hostName.'/'.$rootProject.'/web/app_dev.php/payment/end/stripe';
			else
			$url=$protocol.$hostName.'/web/payment/end/stripe';
			}else{
			if($rootProject!="www")
			$url=$protocol.$hostName.'/'.$rootProject.'/web/app_dev.php/payment/validate';
			else
			$url=$protocol.$hostName.'/web/payment/validate';
			}
    		 
    		 
    echo '<form action="'.$url.'" method="POST">
			 <input type="hidden" name="amount" value="'.$amount.'">
			  <input type="hidden" name="item_number" value="'.$this->getId().'">
			  <input type="hidden" name="currency_code" value="'.$currencyCode.'">
			    <input type="hidden" name="custom" value="'.$this->getUtilisateurs()[0]->getId().'">
			
			
			  <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			    data-key="'.$stripe_pkey.'"
			    data-amount="'.$amount.'"
			    data-name="Stripe"
			    data-description="Beneen Trip charge : Reservation_'.$this->getId().'"
			    data-image="'.$imgbouton.'"
			    data-locale="auto"
			    data-currency="'.$currencyCode.'">
			  </script>
			</form>';
    
    }
    
    
    function calculerMontantTotal()
    	{
    			$somme=0.0;
    				
    			foreach($this->getActivites() as $activite)	
    			$somme+=floatval($activite->getPrixIndividu());	
    			return $somme;	
    	}
    	

    	public function estDansReservation(\BusinessModelBundle\Entity\Activite $activite){
    	
    	foreach($this->getActivites() as $elem){
    	if($elem->getId()==$activite->getId())
    	return true;	
    	}
    	return false;	
    	}
    	
    	
    	function calculerMontantTaxe()
    	{	
    			return (floatval(self::TAX_RATE) / 100) * $this->calculerMontantTotal();	
    	}
    	
    	
    	function calculerMontantTotalAvecTaxe()
    	{	
    			//return ($this->calculerMontantTotal() + $this->calculerMontantTaxe());	
    			 return ($this->calculerMontantTotal() + $this->calculerMontantTaxeActiviteTotal());	
    	}
    	
    	public function compterActivites()
    	{
    		$count=0;
    		
    		foreach($this->getActivites() as $elem)
    		$count++;	
    		
    		return $count;
    	}
    	
    	
    	public function calculerMontantTaxeActivite(\BusinessModelBundle\Entity\Activite $activite){
    	
    	$dateReserve=$this->getDateCreation();
    	$dateActivite=new \DateTime(date_format($activite->getDate(),'Y-m-d').' '.date_format($activite->getHeure(),'H:i'));
    	//Difference in hours
    	$diff =  floor(($dateActivite->getTimestamp() - $dateReserve->getTimestamp())/3600);
		
		if($diff<3)
		return (floatval("20.00") / 100) * $activite->getPrixIndividu();
		else if($diff>=3 && $diff <=12)
		return (floatval("15.00") / 100) * $activite->getPrixIndividu();
		else 
	   return (floatval("10.00") / 100) * $activite->getPrixIndividu();
	   
    	}
    	
    	public function calculerTauxTaxeActivite(\BusinessModelBundle\Entity\Activite $activite){
    	
    	$dateReserve=$this->getDateCreation();
    	$dateActivite=new \DateTime(date_format($activite->getDate(),'Y-m-d').' '.date_format($activite->getHeure(),'H:i'));
    	//Difference in hours
    	$diff =  floor(($dateActivite->getTimestamp() - $dateReserve->getTimestamp())/3600);
		
		if($diff<3)
		return "20.00";
		else if($diff>=3 && $diff <=12)
		return "15.00";
		else 
	   return "10.00";
	   
    	}
    	
    	function calculerMontantTotalActiviteAvecTaxe(\BusinessModelBundle\Entity\Activite $activite)
    	{	
    			//return ($this->calculerMontantTotal() + $this->calculerMontantTaxe());	
    			 return ($activite->getPrixIndividu() + $this->calculerMontantTaxeActivite($activite));	
    	}
    	
    	function calculerMontantTaxeActiviteTotal()
    	{
    			$somme=0.0;
    				
    			foreach($this->getActivites() as $activite)	
    			$somme+=$this->calculerMontantTaxeActivite($activite);	
    			return $somme;	
    	}
    	
    	//Fonction permettant d'extraire la liste des activites cree par l'utilisateurs precis dans la reservation
		function getListActivitesUtilisateur(\BusinessModelBundle\Entity\User $utilisateur){
		
		$listeActivites=array();
		
		foreach($this->getActivites() as $activite)	{
		if($activite->getAuteur()->getId()==$utilisateur->getId())
		$listeActivites[]=$activite;	
		}
			
		return $listeActivites;	 
		}
		
		//Fonction permettant la somme de la liste des activites cree par l'utilisateurs precis dans la reservation
		function getSommeActivitesUtilisateur(\BusinessModelBundle\Entity\User $utilisateur){
		
		$somme=0.0;
		
		foreach($this->getActivites() as $activite)	{
		if($activite->getAuteur()->getId()==$utilisateur->getId())
		$somme+=floatval($activite->getPrixIndividu());	
		}
			
		return $somme;	 
		}
		
		//Fonction permettant d'extraire la liste des utilisateurs sans redondances ayant cree des activites dans la reservation
		function getListUtilisateurAuteurs(){
		
		$listeUsers=array();
		
		foreach($this->getActivites() as $activite)	{
		if(!$this->estdansListe($listeUsers,$activite->getAuteur()))
		$listeUsers[]=$activite->getAuteur();	
		}
			
		return $listeUsers;	 
		}
		
		//Notre petite fonction pour verifier que la presence des utilisateurs dans les listes cela pour eviter les redondances
		function estdansListe($listeUsers,$userId){
			
			foreach($listeUsers as $user){
			if($user->getId()==$userId->getId())
			return true;
			}
			
			return false;
			}
 	
    	
}
