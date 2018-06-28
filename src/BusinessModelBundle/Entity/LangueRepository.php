<?php

namespace BusinessModelBundle\Entity;

/**
 * LangueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LangueRepository extends \Doctrine\ORM\EntityRepository
{
	
				public function myFindAll()
				{
				return $this->createQueryBuilder('l')->getQuery()->getResult();
				}


				public function myFindOne($id)
				{
				// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
				$qb = $this->createQueryBuilder('l')
				->where('l.id = :id')
				->setParameter('id', $id);
				return ($qb->getQuery()->getResult()==null)? null : $qb->getQuery()->getResult()[0];
				}

}
