<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findPageByTitle(string $title): ?Page
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.sections', 's')
            ->addSelect('s')
            ->where('p.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPageById(int $id): ?Page
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.sections', 's')
            ->addSelect('s')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
