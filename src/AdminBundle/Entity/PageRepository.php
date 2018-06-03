<?php

namespace AdminBundle\Entity;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends \Doctrine\ORM\EntityRepository
{
	public function myFindAll()
{
return $this->createQueryBuilder('p')->getQuery()->getResult();
}

public function myFindOne($id)
{
// On passe par le QueryBuilder vide de l'EntityManager pour l'exemple
$qb = $this->createQueryBuilder('p')
->where('p.id = :id')
->setParameter('id', $id);
return $qb->getQuery()->getResult()[0];
}


}
