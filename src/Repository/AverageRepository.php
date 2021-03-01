<?php

namespace App\Repository;

use App\Entity\Average;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Average|null find($id, $lockMode = null, $lockVersion = null)
 * @method Average|null findOneBy(array $criteria, array $orderBy = null)
 * @method Average[]    findAll()
 * @method Average[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AverageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Average::class);
    }

    private function findAllQuery()
    {
        return $this->createQueryBuilder('a');
    }

}
