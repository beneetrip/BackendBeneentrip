<?php

namespace BusinessModelBundle\Entity;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends \Doctrine\ORM\EntityRepository
{
			
			public function myFindAll()
			{
			return $this->createQueryBuilder('i')->getQuery()->getResult();
			}

			
			public function myFindOne($id)
			{
			// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
			$qb = $this->createQueryBuilder('i')
			->where('i.id = :id')
			->setParameter('id', $id);
			return $qb->getQuery()->getResult()[0];
			}

}