<?php

namespace BusinessModelBundle\Entity;

/**
 * PageRepository
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
			->setParameter('lieuDestination', '%'.$lieuDestination.'%');
			return $qb->getQuery()->getResult();
			} 
			
			public function myFindByDate($dateDebut, $dateFin)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.date BETWEEN :dateDebut AND :dateFin')
			->setParameter('dateDebut', new \Datetime($dateDebut))
			->setParameter('dateFin', new \Datetime($dateFin));
			return $qb->getQuery()->getResult();
			}
			
			public function myFindByHeure($heureDebut, $heureFin)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.heure BETWEEN :heureDebut AND :heureFin')
			->setParameter('heureDebut', new \Datetime($heureDebut))
			->setParameter('heureFin', new \Datetime($heureFin));
			return $qb->getQuery()->getResult();
			}

			public function myFindByCategorie($categorie)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('a')
			->where('a.categorie.nom = :categorie')
			->setParameter('categorie', $categorie);
			return $qb->getQuery()->getResult();
			}
			
			public function myFindByAuteur($auteur)
			{
				// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
				$qb = $this->createQueryBuilder('a')
				->where('a.auteur.nomComplet LIKE :auteur OR a.auteur.username LIKE :auteur')
				->setParameter('auteur', '%'.$auteur.'%');
				return $qb->getQuery()->getResult();
			}
			
			
			//Fonction pour les recherches selon les criteres specifiques sur les activites
			public function myFindSurActivites($lieuDestination, $dateDebut, $dateFin, $heureDebut, $heureFin, $categorie, $auteur, $nbResults, $indexDebut)
			{
				//On initialise notre liste de retour au tableau vide			
				$listeRetour=[];
				//Ensuite on teste les criteres en parametres celui qui est non null on appelle sa methode de recherche
				
				if($lieuDestination!=null)
				$listeRetour=array_merge($listeRetour,$this->myFindByLieuDestination($lieuDestination));
				
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
			
			public function myFindListeDestinations()
			{
				$qb=$this->_em->createQueryBuilder()->select('a.lieuDestination')->from($this->_entityName, 'a');
				return $qb->getQuery()->getResult();
			}
			
}
