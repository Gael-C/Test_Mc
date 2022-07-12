<?php

namespace App\Repository;

use App\Entity\Comptes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comptes>
 *
 * @method Comptes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comptes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comptes[]    findAll()
 * @method Comptes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptesRepository extends ServiceEntityRepository
{

	/**
	 * @throws Exception
	 */
	public function findComptes(string $uuid,string $login, string $password) : array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  * from comptes
		WHERE uuid =:uuid  
		  AND login =:login 
		  AND password =:password');

		$stmt->bindParam(':uuid',$uuid);
		$stmt->bindParam(':login',$login);
		$stmt->bindParam(':password',$password);

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();

	}
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comptes::class);
    }

    public function add(Comptes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comptes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Comptes[] Returns an array of Comptes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comptes
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
