<?php

namespace App\Controller;

use App\Repository\ComptesRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
