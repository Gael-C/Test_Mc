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

	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Comptes::class);
	}

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


	/**
	 * @throws Exception
	 */
	public function addComptes(string $uuid, string $login, string $password, string $name): void
    {
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('INSERT INTO comptes (uuid, login, password, name, updated_at)
			VALUES (:uuid, :login, :password,:name,null )
			');
		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':login', $login);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':name', $name);

		$stmt->executeStatement();
    }

	/**
	 * @throws Exception
	 */
	public function updateCompte(string $login, string $password, string $name, string $updated_at, string $uuid, string $password2):void
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('UPDATE `comptes` SET `password` =:password, `name` =:name, `updated_at`=:updated_at
											WHERE `uuid` =:uuid AND `login` =:login AND `password` =:password2');

		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':updated_at', $updated_at);
		$stmt->bindParam(':login', $login);
		$stmt->bindParam(':password2', $password2);
		$stmt->bindParam(':uuid', $uuid);

		$stmt->executeStatement();
	}

	/**
	 * @throws Exception
	 */
	public function removeCompte(string $uuid, string $login, string $pass):void
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('DELETE FROM comptes WHERE `uuid` =:uuid AND `login` =:login AND `password` =:password');

		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':login', $login);
		$stmt->bindParam(':password', $pass);

		$stmt->executeStatement();
	}

	/**
	 * @throws Exception
	 */
	public function listAllComptes() : array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  c.uuid, c.login FROM comptes c 
        							');

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();

	}

	/**
	 * @throws Exception
	 */
	public function listAll() : array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  c.uuid FROM comptes c 
        							LEFT JOIN
       										 (SELECT  e.compte_uuid FROM ecritures e
       										  INNER JOIN comptes c
       										  ON c.uuid = e.compte_uuid
            								  GROUP BY e.compte_uuid
       										 )
       										e ON e.compte_uuid = c.uuid ');

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();

	}
}
