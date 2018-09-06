<?php

namespace BusinessModelBundle\Entity;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends \Doctrine\ORM\EntityRepository
{
			public function myFindAll()
			{
			return $this->createQueryBuilder('r')->getQuery()->getResult();
			}

			public function myFindOne($id)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('r')
			->where('r.id = :id')
			->setParameter('id', $id);
			return ($qb->getQuery()->getResult()==null)? null : $qb->getQuery()->getResult()[0];
			}
			
			//Fonction pour les recherches selon les criteres specifiques sur les Reservations
			public function myFindSurReservations($activites, $utilisateurs, $paye, $dateDebut, $dateFin)
			{

			$qb = $this->createQueryBuilder('r');
			$nbhits=0;//Ce compteur est pour le nombre de criteres valides	
			
			
			if(isset($activites) && trim($activites,'')!=""){
			$qb->join('r.activites', 'a', 'WITH', '(UPPER(a.lieuDestination) LIKE UPPER( :activites ) OR UPPER(a.libelle) LIKE UPPER( :activites ))')
			->setParameter('activites', '%'.$activites.'%');						
			$nbhits++;										
			}
			
			if(isset($utilisateurs) && trim($utilisateurs,'')!=""){
			$qb->join('r.utilisateurs', 'u', 'WITH', 'UPPER(u.nom) LIKE UPPER( :utilisateurs ) OR UPPER(u.prenom) LIKE UPPER( :utilisateurs ) OR UPPER(u.username) LIKE UPPER( :utilisateurs )')
			->setParameter('utilisateurs', '%'.$utilisateurs.'%');						
			$nbhits++;										
			}
			
			if(isset($paye) && trim($paye,'')!=""){
			if($nbhits>0)	
			$qb->andWhere('r.paye = :paye')
			->setParameter('paye', $paye);
			else 
			$qb->where('r.paye = :paye')
			->setParameter('paye', $paye);
			$nbhits++;						
			}
			
			if(isset($dateDebut) && trim($dateDebut,'')!=""){
			
			//On formatte bien notre date pour les requetes			
			$date1=new \DateTime($dateDebut);
		   $dateDebut=$date1->format('Y-m-d');	
		   
			if($nbhits>0)	
			$qb->andWhere('r.dateCreation >= :dateDebut')
			->setParameter('dateDebut', $dateDebut);
			else 
			$qb->where('r.dateCreation >= :dateDebut')
			->setParameter('dateDebut', $dateDebut);
			$nbhits++;						
			}	
			
			if(isset($dateFin) && trim($dateFin,'')!=""){
				

			//On formatte bien notre date pour les requetes			
			$date2=new \DateTime($dateFin);
		   $dateFin=$date2->format('Y-m-d');				
				
			if($nbhits>0)	
			$qb->andWhere('r.dateCreation <= :dateFin')
			->setParameter('dateFin', $dateFin);
			else 
			$qb->where('r.dateCreation <= :dateFin')
			->setParameter('dateFin', $dateFin);
			$nbhits++;						
			}	
			
			/*print_r(array(
  			'sql'  => $qb->getQuery()->getSQL(),
  			'parameters' => $qb->getQuery()->getParameters(),
  			));*/				
		
			
			//Et pour finir on prend les resultats avec tous les criteres en compte
			$listeRetour=$qb->getQuery()->getResult();
			
			//print_r($listeRetour);
			
			return $listeRetour;
				
			}
			
			
			//Fonction qui renvoit le nombre de participants de reservations en fonction d'un utilisateur et/ou d'une activite
			public function nombreReservationsDeja($reservationId, $activiteId, $utilisateurId)
			{
				$qb = $this->createQueryBuilder('r');
				
			if(isset($reservationId) && trim($reservationId,'')!="")
			{
				$qb->where('r.id = :reservationId')
			   ->setParameter('reservationId', $reservationId);
		   }
		      
			if(isset($activiteId) && trim($activiteId,'')!=""){
			$qb->join('r.activites', 'a', 'WITH', 'a.id = :activiteId')
			->setParameter('activiteId', intval($activiteId));						
			}
			
			if(isset($utilisateurId) && trim($utilisateurId,'')!=""){
			$qb->join('r.utilisateurs', 'u', 'WITH', 'u.id = :utilisateurId')
			->setParameter('utilisateurId', intval($utilisateurId));						
			}
			
			/*print_r(array(
  			'sql'  => $qb->getQuery()->getSQL(),
  			'parameters' => $qb->getQuery()->getParameters(),
  			));*/				
		
			
			//Et pour finir on prend les resultats avec tous les criteres en compte
			$listeRetour=$qb->getQuery()->getResult();
			
			//print_r($listeRetour);
			
			//On calcule le nombre total des participants de la liste des reservations			
			$somme=0;
			
			foreach($listeRetour as $reservation)	
			$somme+=count($reservation->getUtilisateurs());
			
			return $somme;
			
			}
			
			//Notre petite fonction pour verifier que la presence des activites dans les listes cela pour eviter les redondances
			public function estdansListe($listActivites,$activiteId){
			
			foreach($listActivites as $activite){
			if($activite->getId()==$activiteId->getId())
			return true;
			}
			
			return false;
			}
			
			//Fonction pour les historiques des activites sur les Reservations Utilisateurs
			//type =0 :Guide; type=1: Touriste
			//evenement=0: passe; evenement=1: en cours; evenement=2: a venir
			public function myFindHistoriqueSurReservations($utilisateurs, $type, $evenement)
			{

			$qb = $this->createQueryBuilder('r');
			
			//Si c'est un touriste
			if($type=='1'){
			if(isset($utilisateurs) && trim($utilisateurs,'')!=""){
			$qb->join('r.utilisateurs', 'u', 'WITH', 'UPPER(u.nom) LIKE UPPER( :utilisateurs ) OR UPPER(u.prenom) LIKE UPPER( :utilisateurs ) OR UPPER(u.username) LIKE UPPER( :utilisateurs )')
			->setParameter('utilisateurs', '%'.$utilisateurs.'%');						
			}
			}
			//Si c'est un Guide
			else {
			if(isset($utilisateurs) && trim($utilisateurs,'')!=""){
			$qb->join('r.activites', 'a')
			->join('a.auteur','u','WITH','UPPER(u.nom) LIKE UPPER( :utilisateurs ) OR UPPER(u.prenom) LIKE UPPER( :utilisateurs ) OR UPPER(u.username) LIKE UPPER( :utilisateurs )')
			->setParameter('utilisateurs', '%'.$utilisateurs.'%');												
			}
			}
			
			//On n'oublie pas de prendre les reservations payes car ce sont eux qui sont concernees par les historiques
			$paye='1';
			$qb->where('r.paye = :paye')->setParameter('paye', $paye);
			
			/*print_r(array(
  			'sql'  => $qb->getQuery()->getSQL(),
  			'parameters' => $qb->getQuery()->getParameters(),
  			));*/				
		
			
			//Et pour finir on prend les resultats avec tous les criteres en compte
			$listeRetour=$qb->getQuery()->getResult();
			
			//print_r($listeRetour);
				
				//On va extraire donc manuellement des reservations les activites concernees par l'historique
				$listeActivites=array();
			
				$dateNow=new \Datetime();

				$date=$dateNow->format('Y-m-d');
				
				foreach($listeRetour as $reservation){
    				
    			foreach($reservation->getActivites() as $activite)	{
    			$dateActivite= new \DateTime(date_format($activite->getDate(),'Y/m/d'));
    			$d=new \DateTime($date);
    			//Si on veut les historiques du passe
    			if($evenement=='0'){
				if($dateActivite<$d && !$this->estdansListe($listeActivites,$activite))
				$listeActivites[]=$activite;
				}
				//Si on veut les historiques en cours
				else if($evenement=='1'){
				if($dateActivite==$d && !$this->estdansListe($listeActivites,$activite))
				$listeActivites[]=$activite;
				}
		
				else{
				if($dateActivite>$d && !$this->estdansListe($listeActivites,$activite))
				$listeActivites[]=$activite;
				}
    			}
    			
    			}
    			usort($listeActivites, array($this, "comparerActivite"));
    			
    			return $listeActivites;	
				
			}
			
			 
			public function comparerActivite($activiteA, $activiteB) {
			
			$dateActiviteA=new \DateTime(date_format($activiteA->getDate(),'Y-m-d').' '.date_format($activiteA->getHeure(),'H:i'));
			$dateActiviteB=new \DateTime(date_format($activiteB->getDate(),'Y-m-d').' '.date_format($activiteB->getHeure(),'H:i'));
				 
			return ($dateActiviteA <= $dateActiviteB) ? 1 : 0;
			
			}
			
			/*
			//Fonction pour les historiques des activites sur les Activites des guides Utilisateurs
			public function myFindHistoriqueReservations($utilisateurs, $dateDebut, $dateFin, $heureDebut, $heureFin)
			{

			$qb = $this->createQueryBuilder('r');
			
			if(isset($utilisateurs) && trim($utilisateurs,'')!=""){
			$qb->join('r.utilisateurs', 'u', 'WITH', 'UPPER(u.nom) LIKE UPPER( :utilisateurs ) OR UPPER(u.prenom) LIKE UPPER( :utilisateurs ) OR UPPER(u.username) LIKE UPPER( :utilisateurs )')
			->setParameter('utilisateurs', '%'.$utilisateurs.'%');						
			}
			
			$req="";
			$joindre=false;
			$parameters=array();
			
			if(isset($dateDebut) && trim($dateDebut,'')!=""){
			if($req=="")
			$req.="(a.date >= :dateDebut)";
			else
			$req.="AND (a.date >= :dateDebut)";	
			
			if(isset($heureDebut) && trim($heureDebut,'')!=""){
			$req.="OR (a.date = :dateDebut AND a.heure >= :heureDebut)";
			$parameters["heureDebut"]=$heureDebut;																				
			}
			
			$joindre=true;
			//On formatte bien notre date pour les requetes			
			$d=new \DateTime($dateDebut);	
			$parameters["dateDebut"]=$d->format('Y-m-d');
			}
			
			if(isset($dateFin) && trim($dateFin,'')!=""){
			
			if($req=="")
			$req.="(a.date <= :dateFin)";
			else
			$req.="AND (a.date <= :dateFin)";
			
			if(isset($heureFin) && trim($heureFin,'')!=""){
			$req.="OR (a.date = :dateFin AND a.heure <= :heureFin)";
			$parameters["heureFin"]=$heureFin;																				
			}
			
			$joindre=true;
			//On formatte bien notre date pour les requetes			
			$d=new \DateTime($dateFin);	
			$parameters["dateFin"]=$d->format('Y-m-d');													
			}
			
					
			if($joindre){
			$jointure=$qb->join('r.activites', 'a', 'WITH', $req);
			foreach ($parameters as $key => $value)
			$jointure->setParameter(''.$key, $value);
			}
			
			//$qb->select('a2')->from('BusinessModelBundle\Entity\Activite','a2');
			//print_r(array(
  			//'sql'  => $qb->getQuery()->getSQL(),
  			//'parameters' => $qb->getQuery()->getParameters(),
  			//));				
		
			
			//Et pour finir on prend les resultats avec tous les criteres en compte
			$listeRetour=$qb->getQuery()->getResult();
			
			//print_r($listeRetour);
			
			return $listeRetour;
				
			}*/
			
			

}
