<?php

namespace App\Repository;

use App\Entity\Ecritures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ecritures>
 *
 * @method Ecritures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecritures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecritures[]    findAll()
 * @method Ecritures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcrituresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecritures::class);
    }

    public function add(Ecritures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	/**
	 * @throws Exception
	 */
	public function findEcritures(string $uuid) : array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  e.label, e.date, e.type, e.amount, e.created_at,e. updated_at
		FROM ecritures e 
		JOIN comptes c on c.uuid = e.compte_uuid
		WHERE c.uuid =:uuid ');

		$stmt->bindParam(':uuid',$uuid);

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();


	}

    public function remove(Ecritures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Ecritures[] Returns an array of Ecritures objects
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

//    public function findOneBySomeField($value): ?Ecritures
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
