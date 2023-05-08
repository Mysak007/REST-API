<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\Pagination;
use App\Model\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filterUsers(UserFilter $filter): Pagination
    {
        $query = $this->createQueryBuilder('u')
            ->setFirstResult($filter->getOffset())
            ->setMaxResults($filter->getLimit());

        if($filter->getSortByColumn() && $filter->getSortDirection()) {
            $query->orderBy("u.{$filter->getSortByColumn()}", $filter->getSortDirection());
        }else{
            $query->orderBy('u.id', $filter->getSortDirection());
        }

        $users = $query->getQuery()->getResult();

        return new Pagination(
            $filter,
            $users,
            $this->count([]),
        );
    }
}
