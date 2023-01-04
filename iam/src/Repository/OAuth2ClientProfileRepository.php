<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\OAuth2ClientProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OAuth2ClientProfile>
 *
 * @method OAuth2ClientProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuth2ClientProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuth2ClientProfile[]    findAll()
 * @method OAuth2ClientProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuth2ClientProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuth2ClientProfile::class);
    }

    public function add(OAuth2ClientProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuth2ClientProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
