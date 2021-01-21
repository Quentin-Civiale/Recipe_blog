<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function getPaginatedRecipes(int $page, int $limit): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder("r")
                ->orderBy("r.publishedAt", "desc")
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit)
        );
    }

    public function search($title = null, $name = null)
    {
        $query = $this->createQueryBuilder('r');
        if ($title !== null) {
            $query->where('MATCH_AGAINST(r.title) AGAINST (:title boolean)>0')
                ->setParameter('title', $title);
        }
        if ($name !== null) {
            $query->leftJoin('r.category', 'c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $name);
        }
        return $query->getQuery()->getResult();
    }
}
