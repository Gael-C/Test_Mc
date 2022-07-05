<?php

	namespace App\Controller;

	use App\Entity\Ecritures;
	use App\Repository\EcrituresRepository;
	use Doctrine\DBAL\Exception;
	use Doctrine\ORM\EntityManagerInterface;
	use Ramsey\Uuid\Uuid;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
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
		 * @throws Ramsey
		 */
		public function create(Request $request, SerializerInterface $seria, EntityManagerInterface $em)
		{
			$t_d = date_create('');
			$today_date = $t_d->format('m.d.Y');
			$c_uuid = $request->attributes->get('uuid');

			if (!empty($c_uuid))
			{
				$jsonRecu = $request->getContent();

				$ecritures = $seria->deserialize($jsonRecu, Ecritures::class, 'json');

				$uuid = $ecritures->setUuid(Uuid::uuid4())->__toString();
				$label = $ecritures->getLabel();
				$type = $ecritures->getType();
				$amount = $ecritures->getAmount();
				$d = $ecritures->getDate();
				$date = $d->format('m.d.Y');

				if ($date >= $today_date && $amount > 0)
				{
					$conn = $em->getConnection();

					$stmt = $conn->prepare('INSERT INTO ecritures (uuid, compte_uuid, label, date, type, amount)
			VALUES (:uuid, :c_uuid, :label,:date, :type, :amount)
			');
					$stmt->bindParam(':uuid', $uuid);
					$stmt->bindParam(':c_uuid', $compte_uuid);
					$stmt->bindParam(':label', $label);
					$stmt->bindParam(':date', $date);
					$stmt->bindParam(':type', $type);
					$stmt->bindParam(':amount', $amount);

					$stmt->executeStatement();

					return new Response('Uuid créé avec succès', 201);

				} else
				{
					return new Response('Date et/ou montant non valide, merci de renseigner une date supérieur à la date du jour et/ou un montant positif', 404);
				}
			}else
			{
				return new Response('Merci d\'entrer un numéro de compte valide', 404);
			}
		}
	}
