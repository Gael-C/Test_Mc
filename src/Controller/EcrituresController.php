<?php

	namespace App\Controller;

	use App\Entity\Ecritures;
	use App\Exception\AmountException;
	use App\Repository\EcrituresRepository;
	use Doctrine\DBAL\Exception;
	use Ramsey\Uuid\Uuid;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use App\Exception\DateException;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
	use Symfony\Component\Serializer\SerializerInterface;

	class EcrituresController extends AbstractController
	{
		/**
		 * @Route("/api/comptes/{uuid}/ecritures", name="api_ecritures",methods={"GET"})
		 * @throws Exception
		 * @throws \Exception
		 */
		public function list(EcrituresRepository $repo, Request $request,NormalizerInterface $norma)
		{
			$uuid = $request->attributes->get('uuid');

			if (!Uuid::isValid($uuid)){
				throw new \Exception('Merci de rentrer un Uuid correct',99 );
			}

			$ecritures = $repo->findEcritures($uuid);

			$array = ['items'=>$ecritures];
			if (!empty($ecritures)) {

				return $this->json($array, 200, [
					'Access-Control-Allow-Origin'=>'*',
					'Access-Control-Allow-Credentials'=> true
				]);

			} else {

				return new Response('Aucunes écritures trouves avec cet uuid', 404);

			}
		}

		/**
		 * @Route("/api/comptes/{uuid}/ecritures", name="api_ecritures_Post", methods={"POST"})
		 * @throws \Exception
		 */
		public function create(Request $request, SerializerInterface $seria,EcrituresRepository $repo): Response
		{
			$c_uuid = $request->attributes->get('uuid');

			if (!Uuid::isValid($c_uuid)){
				throw new \Exception('Merci de rentrer un Uuid correct',99 );
			}

			if (!empty($c_uuid)) {
				$jsonRecu = $request->getContent();
				$parsed = json_decode($jsonRecu, true);
				$amount = array('amount' =>(float)$parsed['amount']);
				$json = array_replace($parsed,$amount);
				$json = json_encode($json);

				$ecritures = $seria->deserialize($json, Ecritures::class, 'json');


				$uuid = $ecritures->setUuid(Uuid::uuid4())->__toStringUuid();
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

						$repo->createEcriture($uuid, $c_uuid, $label, $date, $type, $amount);

						return new Response('Uuid créé avec succès', 201,[
							'Access-Control-Allow-Origin'=>'*',
							'Access-Control-Allow-Credentials'=> true
						]);

					} catch (Exception $e) {
						echo $e->getMessage();
					}
			}
			return new Response('Merci d\'entrer un numéro de compte valide', 400);
		}

		/**
		 * @Route("/api/comptes/{c_uuid}/ecritures/{uuid}", name="api_ecritures_Put", methods={"PUT"})
		 * @throws \Exception
		 */
		public function update(Request $request,EcrituresRepository $repo): JsonResponse
		{
			$c_uuid = $request->attributes->get('c_uuid');

			if (!Uuid::isValid($c_uuid)){
				throw new \Exception('Merci de rentrer un Uuid correct',99 );
			}

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

					$repo->updateEcriture($label,$date,$type,$amount,$updated_at, $uuid, $c_uuid);

					return $this->json(new Response('Modification effectuée', 204));

				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
			return $this->json(new Response('Un soucis a été rencontré, merci de recommencer', 400));
		}

		/**
		 * @Route("/api/comptes/{c_uuid}/ecritures/{uuid}", name="api_ecritures_Delete", methods={"DELETE"})
		 * @throws Exception
		 * @throws \Exception
		 */
		public function delete(Request $request, EcrituresRepository $repo):JsonResponse
		{
			$c_uuid = $request->attributes->get('c_uuid');
			$uuid = $request->attributes->get('uuid');

			if (!Uuid::isValid($uuid )|| !Uuid::isValid($c_uuid)){
				throw new \Exception('Merci de rentrer un Uuid correct',99 );
			}

			if (!empty($c_uuid) || !empty($uuid)){

				$repo->removeEcriture($uuid, $c_uuid);

				return $this->json('Supression effectuée',204);
			}
				return $this->json('Erreur dans la suppression',400);
		}
	}
