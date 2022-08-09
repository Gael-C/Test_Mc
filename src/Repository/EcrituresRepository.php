<?php

namespace App\Repository;

use App\Entity\Ecritures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ecritures>
 *
 * @method Ecritures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecritures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecritures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcrituresRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Ecritures::class);
	}

	/**
	 * @throws Exception
	 */
	public function findEcritures(string $uuid): array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  e.uuid, e.label, e.date, e.type, e.amount, e.created_at,e. updated_at
		FROM ecritures e 
		WHERE e.compte_uuid =:uuid ');

		$stmt->bindParam(':uuid', $uuid);

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();

	}

	/**
	 * @throws Exception
	 */
	public function findOne(string $c_uuid, string  $uuid): array
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT  *
		FROM ecritures 
		WHERE compte_uuid =:c_uuid  AND uuid =:uuid');

		$stmt->bindParam(':c_uuid', $c_uuid);
		$stmt->bindParam(':uuid', $uuid);

		$rs = $stmt->executeQuery();

		return $rs->fetchAllAssociative();

	}

	/**
	 * @throws Exception
	 */
	public function createEcriture(string $uuid, string $c_uuid, string $label, string $date, string $type, float $amount):void
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('INSERT INTO ecritures (uuid, compte_uuid, label, date, type, amount, updated_at)
			VALUES (:uuid, :c_uuid, :label,:date, :type, :amount, null)
			');
		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':c_uuid', $c_uuid);
		$stmt->bindParam(':label', $label);
		$stmt->bindParam(':date', $date);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':amount', $amount);

		$stmt->executeStatement();

	}

	/**
	 * @throws Exception
	 */
	public function updateEcriture(string $label, string $date, string $type, float $amount, string $updated_at,string $uuid, string $c_uuid):void
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('UPDATE `ecritures` SET `label` =:label, `date` =:date, `type` =:type, `amount` =:amount, `updated_at` =:updated_at 
											WHERE `uuid` =:uuid AND `compte_uuid` =:c_uuid');

		$stmt->bindParam(':label', $label);
		$stmt->bindParam(':date', $date);
		$stmt->bindParam(':updated_at', $updated_at);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':amount', $amount);
		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':c_uuid', $c_uuid);

		$stmt->executeStatement();
	}

	/**
	 * @throws Exception
	 */
	public function removeEcriture(string $uuid, string $c_uuid)
	{
		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('DELETE FROM ecritures WHERE `uuid` =:uuid AND `compte_uuid` =:c_uuid');

		$stmt->bindParam(':uuid', $uuid);
		$stmt->bindParam(':c_uuid', $c_uuid);

		$stmt->executeStatement();
	}


	public function total(string $c_uuid)
	{
		$C = 'C';
		$D = 'D';

		$conn = $this->getEntityManager()->getConnection();

		$stmt = $conn->prepare('SELECT 
							  (SELECT SUM(amount) FROM ecritures WHERE type=:C AND compte_uuid =:c_uuid ) 
   							  - (SELECT SUM(amount) FROM ecritures WHERE type=:D AND compte_uuid =:c_uuid ) AS "total"');

		$stmt->bindParam('c_uuid',$c_uuid);
		$stmt->bindParam('C',$C);
		$stmt->bindParam('c_uuid',$D);

	}

}
