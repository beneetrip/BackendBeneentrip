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
			->join('a.auteur', 'u', 'WITH', 'u.nomComplet LIKE :auteur OR u.username LIKE :auteur')
			->setParameter('auteur', '%'.$auteur.'%');
			return $qb->getQuery()->getArrayResult();
			}
			
			
			/*Fonction pour les recherches selon les criteres specifiques sur les activites
			public function myFindSurActivites($lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, $auteur, $nbResults, $indexDebut)
			{
			//On initialise notre liste de retour au tableau vide			
			$listeRetour=[];
			//Ensuite on teste les criteres en parametres celui qui est non null on appelle sa methode de recherche
			
			if($lieuDestination!=null)
			$listeRetour=array_merge($listeRetour,$this->myFindByLieuDestination($lieuDestination));
			
			//print_r($this->myFindByLieuDestination($lieuDestination));
			if($dateDebut!=null && $dateFin!=null)
			$listeRetour=array_merge($listeRetour,$this->myFindByDate($dateDebut, $dateFin));
			
			if($heureDebut!=null && $heureFin!=null)
			$listeRetour=array_merge($listeRetour,$this->myFindByHeure($heureDebut, $heureFin));
			
			if($categorie!=null)
			$listeRetour=array_merge($listeRetour,$this->myFindByCategorie($categorie));
			
			if($auteur!=null)
			$listeRetour=array_merge($listeRetour,$this->myFindByAuteur($auteur));			
			
			return array_slice($listeRetour, $indexDebut, ($indexDebut+$nbResults));			
			}
			*/
			
			
			public function myFindListeDestinations()
			{
			$qb=$this->_em->createQueryBuilder()->select('a.lieuDestination')->from($this->_entityName, 'a');
			$retour=[];
			
			//On fait un tableau simple pour les destinations
			foreach ($qb->getQuery()->getArrayResult() as $elt)
			$retour[]=$elt['lieuDestination'];			
			
			return $retour;
			}
		
			
			//Fonction pour les recherches selon les criteres specifiques sur les activites
			public function myFindSurActivites($lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, $auteur, $nbResults, $indexDebut)
			{
				
						$qb = $this->createQueryBuilder('a');
						$nbhits=0;//Ce compteur est pour le nombre de criteres valides
						
						
						if($lieuDestination!=null){
						$qb->where('a.lieuDestination LIKE :lieuDestination')
						->setParameter('lieuDestination','%'.$lieuDestination.'%');
						$nbhits++;						
						}
						
						
						if($dateDebut!=null && $dateFin!=null){
						if($nbhits>0)	
						$qb->andWhere('a.date BETWEEN :dateDebut AND :dateFin')
						->setParameter('dateDebut', new \Datetime($dateDebut))
						->setParameter('dateFin', new \Datetime($dateFin));
						else 
						$qb->where('a.date BETWEEN :dateDebut AND :dateFin')
						->setParameter('dateDebut', new \Datetime($dateDebut))
						->setParameter('dateFin', new \Datetime($dateFin));
						
						$nbhits++;						
						}
						
						
						if($heureDebut!=null && $heureFin!=null){
						if($nbhits>0)	
						$qb->andWhere('a.heure BETWEEN :heureDebut AND :heureFin')
						->setParameter('heureDebut', new \Datetime($heureDebut))
						->setParameter('heureFin', new \Datetime($heureFin));
						else 
						$qb->where('a.heure BETWEEN :heureDebut AND :heureFin')
						->setParameter('heureDebut', new \Datetime($heureDebut))
						->setParameter('heureFin', new \Datetime($heureFin));
						
						$nbhits++;						
						}
						
						
						if($categorie!=null){
						$qb->join('a.categorie', 'c', 'WITH', 'c.nom = :categorie')
						->setParameter('categorie', $categorie);							
						$nbhits++;										
						}
						
						
						if($auteur!=null){
						$qb->join('a.auteur', 'u', 'WITH', 'u.nomComplet LIKE :auteur OR u.username LIKE :auteur')
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
			
}
