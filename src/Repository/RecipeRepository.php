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

    private function findAllQuery()
    {
        return $this->createQueryBuilder('r');
    }

    public function findLatestRecipe(): array
    {
        $query = $this->findAllQuery();

        // sélection des 5 dernières annonces créées
        return $query->orderBy('r.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findFavoritesRecipes(): array
    {
        $query = $this->findAllQuery();

        // sélection des recettes misent en favoris
        return $query->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
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

    public function search($title = null, $category = null)
    {
        $query = $this->createQueryBuilder('r');
        if ($title !== null) {
            $query->andWhere('MATCH_AGAINST(r.title) AGAINST (:title boolean)>0')
                ->setParameter('title', $title);
        }
        if ($category !== null) {
            $query->leftJoin('r.category', 'c')
                ->andWhere('c.name LIKE :name')
                ->setParameter('name', '%'.$category.'%');
        }

        $query->orderBy("r.publishedAt", "desc");

        return $query->getQuery()->getResult();
    }
}
