<?php

	namespace App\Controller;

	use App\Entity\Ecritures;
	use App\Exception\AmountException;
	use App\Repository\EcrituresRepository;
	use Doctrine\DBAL\Exception;
	use Doctrine\ORM\EntityManagerInterface;
	use Ramsey\Uuid\Uuid;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use App\Exception\DateException;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Serializer\Exception\ExceptionInterface;
	use Symfony\Component\Serializer\SerializerInterface;

	class ApiController extends AbstractController
	{
		/**
		 * @Route("/api/comptes/{uuid}/ecritures", name="api_post_index",methods={"GET"})
		 * @throws Exception|ExceptionInterface
		 */
		public function index(EcrituresRepository $repo, Request $request)
		{
			$uuid = $request->attributes->get('uuid');

			$ecritures = $repo->findEcritures($uuid);

			if (!empty($ecritures)) {

				return $this->json($ecritures, 200, []);

			} else {

				return new Response('Aucunes écritures trouves avec cet uuid', 404);

			}
		}

		/**
		 * @Route("/api/comptes/{uuid}/ecritures", name="api_post_Post", methods={"POST"})
		 * @throws Exception
		 */
		public function create(Request $request, SerializerInterface $seria, EntityManagerInterface $em)
		{
			$today_date = date_create('');
			$c_uuid = $request->attributes->get('uuid');

			if (!empty($c_uuid)) {
				$jsonRecu = $request->getContent();
				$ecritures = $seria->deserialize($jsonRecu, Ecritures::class, 'json');

				$uuid = $ecritures->setUuid(Uuid::uuid4())
								  ->__toStringUuid();
				$label = $ecritures->getLabel();
				$type = $ecritures->getType();
				$amount = $ecritures->getAmount();
				$d = $ecritures->getDate();
				$date = $d->format('Y-m-d');

				$ts_d = strtotime($date);
				$today_date = date_create('');
				$ts_today = strtotime(date_format($today_date, 'Y-m-d'));

				try {
						(new DateException)->verifDate($ts_today, $ts_d);
						(new AmountException)->verifAmount($amount);

						$conn = $em->getConnection();

						$stmt = $conn->prepare('INSERT INTO ecritures (uuid, compte_uuid, label, date, type, amount)
			VALUES (:uuid, :c_uuid, :label,:date, :type, :amount)
			');
						$stmt->bindParam(':uuid', $uuid);
						$stmt->bindParam(':c_uuid', $c_uuid);
						$stmt->bindParam(':label', $label);
						$stmt->bindParam(':date', $date);
						$stmt->bindParam(':type', $type);
						$stmt->bindParam(':amount', $amount);

						$stmt->executeStatement();

						return new Response('Uuid créé avec succès', 201);

					} catch (Exception $e) {
						echo $e->getMessage();
					}
				}
			return new Response('Merci d\'entrer un numéro de compte valide', 400);
		}

		/**
		 * @Route("/api/comptes/{c_uuid}/ecritures/{uuid}", name="api_post_Put", methods={"PUT"})
		 */
		public function update(Request $request, ?Ecritures $ecritures, EntityManagerInterface $em)
		{
			$c_uuid = $request->attributes->get('c_uuid');

			$donnees = json_decode($request->getContent(), true);


			if (!empty($donnees)) {

				$d = \DateTime::createFromFormat('d.m.Y', $donnees['date']);

				$ecritures = new Ecritures();

				$uuid = $ecritures->setUuid(Uuid::fromString($donnees['uuid']))
								  ->__toStringUuid();
				$label = $ecritures->setLabel($donnees['label'])
								   ->__toStringLabel();
				$type = $ecritures->setType($donnees['type'])
								  ->__toStringType();
				$date = date_format($d, 'Y-m-d');
				$amount = floatval($ecritures->setAmount($donnees['amount'])
											 ->__toStringAmount());
				$updated_at = date_format($d, 'Y-m-d H:i:s');

				$ts_d = strtotime($date);
				$today_date = date_create('');
				$ts_today = strtotime(date_format($today_date, 'Y-m-d'));

				try {
					(new DateException)->verifDate($ts_today, $ts_d);
					(new AmountException)->verifAmount($amount);

					$conn = $em->getConnection();
					$stmt = $conn->prepare('UPDATE `ecritures` SET `label` =:label, `date` =:date, `type` =:type, `amount` =:amount, `updated_at` =:upd_date 
											WHERE `uuid` =:uuid AND `compte_uuid` =:c_uuid');
					$stmt->bindParam(':label', $label);
					$stmt->bindParam(':date', $date);
					$stmt->bindParam(':type', $type);
					$stmt->bindParam(':amount', $amount);
					$stmt->bindParam(':upd_date', $updated_at);
					$stmt->bindParam(':uuid', $uuid);
					$stmt->bindParam(':c_uuid', $c_uuid);

					$stmt->executeStatement();

					return $this->json(new Response('Modification effectuée', 204));

				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
			return $this->json(new Response('Un soucis a été rencontré, merci de recommencer', 400));
		}

		/**
		 * @Route("/api/comptes/{c_uuid}/ecritures/{uuid}", name="api_post_Delete", methods={"DELETE"})
		 * @throws Exception
		 */
		public function delete(Request $request, ?Ecritures $ecritures, EntityManagerInterface $em)
		{
			$c_uuid = $request->attributes->get('c_uuid');
			$uuid = $request->attributes->get('uuid');

			$conn = $em->getConnection();

			if (!empty($c_uuid) || !empty($uuid)){

				$stmt = $conn->prepare('DELETE FROM ecritures WHERE `uuid` =:uuid AND `compte_uuid` =:c_uuid');

				$stmt->bindParam(':uuid', $uuid);
				$stmt->bindParam(':c_uuid', $c_uuid);

				$stmt->executeStatement();

				return $this->json('Supression effectuée',200);
			}
				return $this->json('Erreur dans la suppression',400);
		}
	}
