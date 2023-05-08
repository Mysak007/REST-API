<?php

namespace App\Repository;

use App\Entity\Follower;
use App\Entity\User;
use App\Model\Pagination;
use App\Model\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Follower>
 *
 * @method Follower|null find($id, $lockMode = null, $lockVersion = null)
 * @method Follower|null findOneBy(array $criteria, array $orderBy = null)
 * @method Follower[]    findAll()
 * @method Follower[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follower::class);
    }

    public function add(Follower $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Follower $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUserFollowers(User $user, UserFilter $filter): Pagination
    {
        $query = $this->createQueryBuilder('f')
            ->setFirstResult($filter->getOffset())
            ->setMaxResults($filter->getLimit());

        $query->join('f.follower', 'u');

        $query->where('f.follow = :user');
        $query->setParameter(':user', $user);

        if($filter->getSortByColumn() && $filter->getSortDirection()) {
            $query->orderBy("u.{$filter->getSortByColumn()}", $filter->getSortDirection());
        }else{
            $query->orderBy('f.created', $filter->getSortDirection());
        }

        $followers = $query->getQuery()->getResult();

        return new Pagination(
            $filter,
            $followers,
            $this->count([ 'follow' => $user ])
        );
    }
}
