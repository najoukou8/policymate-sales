<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Deployer\select;

/**
 * @extends ServiceEntityRepository<Sale>
 *
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function add(Sale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findTopProducts(int $limit = 3): array
    {
        $qb= $this->createQueryBuilder('s')
            ->select('s.productSku, s.productName, SUM(s.unitPrice * s.quantity) AS revenue')
            ->groupBy('s.productSku')
            ->orderBy('revenue', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
    public function findMonthlyRevenue(int $year): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select("SUBSTRING(s.orderDate, 1, 7) AS month")
            ->addSelect("SUM(s.unitPrice * s.quantity) AS revenue")
            ->where("SUBSTRING(s.orderDate, 1, 4) = :year")
            ->setParameter('year', (string)$year)
            ->groupBy('month')
            ->orderBy('month', 'ASC');
        return $qb->getQuery()->getResult();

    }
    public function findTopCustomers(int $limit = 3): array
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.customerEmail, SUM(s.unitPrice * s.quantity) AS revenue, count(s.orderId) AS count ')
            ->groupBy('s.customerEmail')
            ->orderBy('revenue', 'DESC')
            ->setMaxResults($limit);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

//    /**
//     * @return Sale[] Returns an array of Sale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sale
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
