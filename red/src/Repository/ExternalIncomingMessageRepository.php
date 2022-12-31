<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExternalIncomingMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExternalIncomingMessage>
 *
 * @method ExternalIncomingMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalIncomingMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalIncomingMessage[]    findAll()
 * @method ExternalIncomingMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalIncomingMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalIncomingMessage::class);
    }

    public function save(ExternalIncomingMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExternalIncomingMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ExternalIncomingMessage[] Returns an array of ExternalIncomingMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExternalIncomingMessage
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
