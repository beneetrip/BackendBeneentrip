<?php

namespace AdminBundle\Entity;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
	public function myFindAll()
{
return $this->createQueryBuilder('c')->getQuery()->getResult();
}

public function myFindOne($id)
{
// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
$qb = $this->createQueryBuilder('c')
->where('c.id = :id')
->setParameter('id', $id);
return $qb->getQuery()->getResult()[0];
}


}
