<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Entity\Ecritures;
use App\Repository\ComptesRepository;
use App\Repository\EcrituresRepository;
use Doctrine\DBAL\Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ComptesController extends AbstractController
{
	/**
	 * @Route("/api/comptes/{uuid}", name="api_comptes", methods={"GET"})
	 * @throws Exception
	 */
    public function list(ComptesRepository $repo, Request $request): Response
    {
		$uuid = $request->attributes->get('uuid');
		$login = $request->headers->get('login');
		$password = $request->headers->get('password');

		$comptes = $repo->findComptes($uuid,$login,$password);

		if (!empty($comptes)) {

			return $this->json($comptes, 200, []);

		} else {

			return new Response('Aucun comptes trouvés avec cet uuid', 404);

		}
	}

	/**
	 * @Route("/api/comptes", name="api_comptes_Post", methods={"Post"})
	 * @throws Exception
	 */

	public function createCompte(Request $request, SerializerInterface $seria, ComptesRepository $repo): Response
	{
		$jsonRecu = $request->getContent();
		$compte = $seria->deserialize($jsonRecu, Comptes::class, 'json');

		if (!empty($compte)){

			$uuid = $compte->setUuid(Uuid::uuid4())->__toStringUuid();
			$login = $compte->getLogin();
			$password = $compte->getPassword();
			$name = $compte->getName();

			$repo->addComptes($uuid,$login,$password,$name);

			return new Response('Uuid créé avec succès', 201);

		}else{

			return new Response('Échec de la création du compte, merci de recommencer',400);
		}
	}

	/**
	 * @Route("/api/comptes/{uuid}", name="api_comptes_Put", methods={"PUT"})
	 * @throws Exception
	 */

	public function updateCompte(Request $request,ComptesRepository $repo): Response
	{
		$uuid = $request->attributes->get('uuid');
		$login = $request->headers->get('login');
		$old_pass = $request->headers->get('password');
		$d = date_create('');


		$donnees = json_decode($request->getContent(), true);


		if (!empty($donnees)){

			$compte = new Comptes();

			$name = $compte->setName($donnees['name'])->__toStringName();
			$password = $compte->setPassword($donnees['password'])->__toStringPass();
			$updated_at = date_format($d, 'Y-m-d H:i:s');

			$repo->updateCompte($login,$password,$name,$updated_at,$uuid,$old_pass);

			return new Response('Compte modifié avec succès', 201);

		}else{

			return new Response('Échec de la modification du compte, merci de recommencer',400);
		}
	}

	/**
	 * @Route("/api/comptes/{uuid}", name="api_compte_Delete", methods={"DELETE","GET"})
	 * @throws Exception|ExceptionInterface
	 */
	public function delete(Request $request, ComptesRepository $repo,EcrituresRepository $e_repo, SerializerInterface $seria):Response
	{
		$uuid = $request->attributes->get('uuid');
		$login = $request->headers->get('login');
		$password = $request->headers->get('password');

		$ecritures = $e_repo->findEcritures($uuid);
		$ecritures = $seria->normalize($ecritures,'json');

		if (!empty($ecritures)){

			throw new Exception('Impossible de supprimer un compte comportant des écritures.',50);

		}else{

			$repo->removeCompte($uuid,$login,$password);

		}
		return new Response('Suppression effectuée',204);

	}


}
