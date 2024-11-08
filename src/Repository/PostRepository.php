<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Find all posts with a limit, offset, and order.
     *
     * @param int $limit
     * @param int $offset
     * @param string $orderByField
     * @param string $orderDirection
     * @return Post[]
     */
    public function findAllPosts(int $limit = 10, int $offset = 0, string $orderByField = 'createdAt', string $orderDirection = 'DESC'): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.' . $orderByField, $orderDirection)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find posts by category.
     *
     * @param int $categoryId
     * @return Post[]
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find posts by author.
     *
     * @param int $authorId
     * @return Post[]
     */
    public function findByAuthor(int $authorId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get the latest posts with pagination.
     *
     * @param int $page
     * @param int $pageSize
     * @return Paginator
     */
    public function findLatestPosts(int $page = 1, int $pageSize = 10): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($pageSize)
            ->setFirstResult(($page - 1) * $pageSize)
            ->getQuery();

        return new Paginator($query);
    }

    /**
     * Search for posts by keyword in name or description.
     *
     * @param string $keyword
     * @return Post[]
     */
    public function searchByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :keyword OR p.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
