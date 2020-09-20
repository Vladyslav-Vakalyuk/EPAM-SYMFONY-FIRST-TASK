<?php
/**
 * Created by PhpStorm.
 * User: vladv
 * Date: 19.09.2020
 * Time: 16:21
 */

namespace App\Repository;

use App\Entity\Oxford;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class OxfordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oxford::class);
    }

    public function findAllUniqueName(): array
    {
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('p')
            ->addSelect('count(p.id) as count')
            ->addOrderBy('count', 'DESC')
            ->groupBy('p.name');

        $query = $qb->getQuery();

        return $query->execute();

        // to get just one result:
        // $product = $query->setMaxResults(1)->getOneOrNullResult();
    }
}