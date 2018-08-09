<?php

namespace BusinessModelBundle\Entity;

/**
 * ActiviteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActiviteRepository extends \Doctrine\ORM\EntityRepository
{
	
			public function myFindAll()
			{
			return $this->createQueryBuilder('a')->getQuery()->getResult();
			}


			public function myFindOne($id)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.id = :id')
			->setParameter('id', $id);
			return ($qb->getQuery()->getResult()==null)? null : $qb->getQuery()->getResult()[0];
			}
			
			public function myFindByLieuDestination($lieuDestination)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.lieuDestination LIKE :lieuDestination')
			->setParameter('lieuDestination','%'.$lieuDestination.'%');
			/*print_r(array(
			'sql'        => $qb->getQuery()->getSQL(),
			'parameters' => $qb->getQuery()->getParameters(),
			));*/
			return $qb->getQuery()->getArrayResult();
			} 
			
			public function myFindByDate($dateDebut, $dateFin)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.date BETWEEN :dateDebut AND :dateFin')
			->setParameter('dateDebut', new \Datetime($dateDebut))
			->setParameter('dateFin', new \Datetime($dateFin));
			return $qb->getQuery()->getArrayResult();
			}
			
			public function myFindByHeure($heureDebut, $heureFin)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.heure BETWEEN :heureDebut AND :heureFin')
			->setParameter('heureDebut', new \Datetime($heureDebut))
			->setParameter('heureFin', new \Datetime($heureFin));
			return $qb->getQuery()->getArrayResult();
			}

			public function myFindByCategorie($categorie)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->join('a.categorie', 'c', 'WITH', 'c.nom = :categorie')
			->setParameter('categorie', $categorie);
			return $qb->getQuery()->getArrayResult();
			}
			
			public function myFindByAuteur($auteur)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->join('a.auteur', 'u', 'WITH', 'u.nom LIKE :auteur OR u.prenom LIKE :auteur OR u.username LIKE :auteur')
			->setParameter('auteur', '%'.$auteur.'%');
			return $qb->getQuery()->getArrayResult();
			}
	
			
			public function myFindListeDestinations()
			{
			$qb=$this->_em->createQueryBuilder()->select('a.lieuDestination')->from($this->_entityName, 'a');
			$retour=[];
			
			//On fait un tableau simple pour les destinations
			foreach ($qb->getQuery()->getArrayResult() as $elt)
			$retour[]=$elt['lieuDestination'];			
			
			return array_unique(array_map("strtoupper", $retour));
			}
			
			//Fonction pour les recherches selon les criteres specifiques sur les activites
			public function myFindSurActivites($lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, $auteur, $nbResults, $indexDebut)
			{
				
						$qb = $this->createQueryBuilder('a');
						$nbhits=0;//Ce compteur est pour le nombre de criteres valides
						
						
						if(isset($lieuDestination) && trim($lieuDestination,'')!=""){
						//On va voir d'abord si lieuDestination n'est pas constituer de plusieurs mots avec des separateurs virgules ou espaces
						// Si lieuDestination contient des virgules						
						if(strpos($lieuDestination,",")!== false)
						$tabMots = explode(",",str_replace(' ', '', $lieuDestination));
						else
						$tabMots = explode(" ",$lieuDestination);
						//S'il ya plusieurs mots on va faire les requetes avec chaque mots en evitant les mots vides						
						if(count($tabMots) > 1){
						if(trim($tabMots[0])!="")	
						$qb->where('UPPER(a.lieuDestination) LIKE UPPER( :mot0 )')
						->setParameter('mot0','%'.$tabMots[0].'%');
						for($i = 1; $i < count($tabMots); $i++){
						if(trim($tabMots[$i])!="")						
						$qb->orWhere('UPPER(a.lieuDestination) LIKE UPPER( :mot'.$i.' )')
						->setParameter('mot'.$i,'%'.$tabMots[$i].'%');
						}
						}	
						else 
						$qb->where('UPPER(a.lieuDestination) LIKE UPPER( :lieuDestination )')
						->setParameter('lieuDestination','%'.$lieuDestination.'%');
						$nbhits++;						
						}
						
						
						if(isset($dateDebut) && trim($dateDebut,'')!=""){
							
						//On formatte bien notre date pour les requetes			
						$date1=new \DateTime($dateDebut);
					   $dateDebut=$date1->format('Y-m-d');
		   	
						if($nbhits>0)
						$qb->andWhere('a.date >= :dateDebut')
						->setParameter('dateDebut', $dateDebut);
						else 
						$qb->where('a.date >= :dateDebut')
						->setParameter('dateDebut', $dateDebut);
						
						$nbhits++;						
						}
						
						if(isset($dateFin) && trim($dateFin,'')!=""){
							
							
						//On formatte bien notre date pour les requetes			
						$date2=new \DateTime($dateFin);
					   $dateFin=$date2->format('Y-m-d');
					   
						if($nbhits>0)
						$qb->andWhere('a.date <= :dateFin')
						->setParameter('dateFin', $dateFin);
						else 
						$qb->where('a.date <= :dateFin')
						->setParameter('dateFin', $dateFin);
						
						$nbhits++;						
						}
						
						
						if(isset($heureDebut) && trim($heureDebut,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.heure >= :heureDebut')
						->setParameter('heureDebut', $heureDebut);
						else 
						$qb->where('a.heure >= :heureDebut')
						->setParameter('heureDebut', $heureDebut);
						
						$nbhits++;						
						}
						
						if(isset($heureFin) && trim($heureFin,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.heure <= :heureFin')
						->setParameter('heureFin', $heureFin);
						else 
						$qb->where('a.heure <= :heureFin')
						->setParameter('heureFin', $heureFin);
						
						$nbhits++;						
						}
						
						
						if(isset($categorie) && trim($categorie,'')!=""){
						$qb->join('a.categorie', 'c', 'WITH', 'UPPER(c.nom) LIKE UPPER( :categorie )')
						->setParameter('categorie', '%'.$categorie.'%');							
						$nbhits++;										
						}
						
						
						if(isset($auteur) && trim($auteur,'')!=""){
						$qb->join('a.auteur', 'u', 'WITH', 'UPPER(u.nom) LIKE UPPER( :auteur ) OR UPPER(u.prenom) LIKE UPPER( :auteur ) OR UPPER(u.username) LIKE UPPER( :auteur )')
						->setParameter('auteur', '%'.$auteur.'%');						
						$nbhits++;										
						}
						
						/*print_r(array(
        				'sql'        => $qb->getQuery()->getSQL(),
        				'parameters' => $qb->getQuery()->getParameters(),
        				));						
						*/
						
						//Et pour finir on prend les resultats avec tous les criteres en compte
						$listeRetour=$qb->getQuery()->getArrayResult();
						
						//print_r($listeRetour);
						
						return array_slice($listeRetour, $indexDebut, $nbResults);			 
						
			}
			
			//Fonction pour les recherches en profondeur selon les criteres specifiques sur les activites
			public function myDeepFindSurActivites($lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $prixIndividuMin
			,$prixIndividuMax, $nbParticipantsMin, $nbParticipantsMax, $categorie, $auteur)
			{
				
				$qb = $this->createQueryBuilder('a');
						$nbhits=0;//Ce compteur est pour le nombre de criteres valides
						
						
						if(isset($lieuDestination) && trim($lieuDestination,'')!=""){
						//On va voir d'abord si lieuDestination n'est pas constituer de plusieurs mots avec des separateurs virgules ou espaces
						// Si lieuDestination contient des virgules						
						if(strpos($lieuDestination,",")!== false)
						$tabMots = explode(",",str_replace(' ', '', $lieuDestination));
						else
						$tabMots = explode(" ",$lieuDestination);
						//S'il ya plusieurs mots on va faire les requetes avec chaque mots en evitant les mots vides						
						if(count($tabMots) > 1){
						if(trim($tabMots[0])!="")	
						$qb->where('UPPER(a.lieuDestination) LIKE UPPER( :mot0 )')
						->setParameter('mot0','%'.$tabMots[0].'%');
						for($i = 1; $i < count($tabMots); $i++){
						if(trim($tabMots[$i])!="")						
						$qb->orWhere('UPPER(a.lieuDestination) LIKE UPPER( :mot'.$i.' )')
						->setParameter('mot'.$i,'%'.$tabMots[$i].'%');
						}
						}	
						else 
						$qb->where('UPPER(a.lieuDestination) LIKE UPPER( :lieuDestination )')
						->setParameter('lieuDestination','%'.$lieuDestination.'%');
						$nbhits++;						
						}
						
						
						if(isset($dateDebut) && trim($dateDebut,'')!=""){
						
						//On formatte bien notre date pour les requetes			
						$date1=new \DateTime($dateDebut);
					   $dateDebut=$date1->format('Y-m-d');							
							
						if($nbhits>0)
						$qb->andWhere('a.date >= :dateDebut')
						->setParameter('dateDebut', $dateDebut);
						else 
						$qb->where('a.date >= :dateDebut')
						->setParameter('dateDebut', $dateDebut);
						
						$nbhits++;						
						}
						
						if(isset($dateFin) && trim($dateFin,'')!=""){
							
						//On formatte bien notre date pour les requetes			
						$date2=new \DateTime($dateFin);
					   $dateFin=$date2->format('Y-m-d');
		   	
						if($nbhits>0)
						$qb->andWhere('a.date <= :dateFin')
						->setParameter('dateFin', $dateFin);
						else 
						$qb->where('a.date <= :dateFin')
						->setParameter('dateFin', $dateFin);
						
						$nbhits++;						
						}
						
						
						if(isset($heureDebut) && trim($heureDebut,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.heure >= :heureDebut')
						->setParameter('heureDebut', $heureDebut);
						else 
						$qb->where('a.heure >= :heureDebut')
						->setParameter('heureDebut', $heureDebut);
						
						$nbhits++;						
						}
						
						if(isset($heureFin) && trim($heureFin,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.heure <= :heureFin')
						->setParameter('heureFin', $heureFin);
						else 
						$qb->where('a.heure <= :heureFin')
						->setParameter('heureFin', $heureFin);
						
						$nbhits++;						
						}
						
						if(isset($prixIndividuMin) && trim($prixIndividuMin,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.prixIndividu >= :prixIndividuMin')
						->setParameter('prixIndividuMin', floatval($prixIndividuMin));
						else 
						$qb->where('a.prixIndividu >= :prixIndividuMin')
						->setParameter('prixIndividuMin', floatval($prixIndividuMin));
						
						$nbhits++;						
						}
						
						if(isset($prixIndividuMax) && trim($prixIndividuMax,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.prixIndividu <= :prixIndividuMax')
						->setParameter('prixIndividuMax', floatval($prixIndividuMax));
						else 
						$qb->where('a.prixIndividu <= :prixIndividuMax')
						->setParameter('prixIndividuMax', floatval($prixIndividuMax));
						
						$nbhits++;						
						}
						
						if(isset($nbParticipantsMin) && trim($nbParticipantsMin,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.nbParticipants >= :nbParticipantsMin')
						->setParameter('nbParticipantsMin', intval($nbParticipantsMin));
						else 
						$qb->where('a.nbParticipants >= :nbParticipantsMin')
						->setParameter('nbParticipantsMin', intval($nbParticipantsMin));
						
						$nbhits++;						
						}
						
						if(isset($nbParticipantsMax) && trim($nbParticipantsMax,'')!=""){
						if($nbhits>0)	
						$qb->andWhere('a.nbParticipants <= :nbParticipantsMax')
						->setParameter('nbParticipantsMax', intval($nbParticipantsMax));
						else 
						$qb->where('a.nbParticipants <= :nbParticipantsMax')
						->setParameter('nbParticipantsMax', intval($nbParticipantsMax));
						
						$nbhits++;						
						}
						
						if(isset($categorie) && trim($categorie,'')!=""){
						$qb->join('a.categorie', 'c', 'WITH', 'UPPER(c.nom) LIKE UPPER( :categorie )')
						->setParameter('categorie', '%'.$categorie.'%');							
						$nbhits++;										
						}
						
						
						if(isset($auteur) && trim($auteur,'')!=""){
						$qb->join('a.auteur', 'u', 'WITH', 'UPPER(u.nom) LIKE UPPER( :auteur ) OR UPPER(u.prenom) LIKE UPPER( :auteur ) OR UPPER(u.username) LIKE UPPER( :auteur )')
						->setParameter('auteur', '%'.$auteur.'%');						
						$nbhits++;										
						}
						
						/*print_r(array(
        				'sql'        => $qb->getQuery()->getSQL(),
        				'parameters' => $qb->getQuery()->getParameters(),
        				));*/			
					
						
						//Et pour finir on prend les resultats avec tous les criteres en compte
						$listeRetour=$qb->getQuery()->getResult();
						
						//print_r($listeRetour);
						
						return $listeRetour;
				
			} 
			
}
