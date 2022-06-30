<?php

	namespace App\Controller;

	use App\Repository\EcrituresRepository;
	use Doctrine\DBAL\Exception;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Serializer\Exception\ExceptionInterface;
	use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

	class ApiController extends AbstractController
	{
		/**
		 * @Route("/api/comptes/{uuid}/ecritures", name="api_post_index",methods={"GET"})
		 * @throws Exception|ExceptionInterface
		 */
		public function index(EcrituresRepository $repo, NormalizerInterface  $norma, Request $request)
		{
			$uuid = $request->attributes->get('uuid');

			$ecritures = $repo->findEcritures($uuid);

			if (!empty($ecritures)){
				$ecrituresNormalises = $norma->normalize($ecritures);

				$json = json_encode($ecrituresNormalises);

				return new Response($json,200,[
					"Content-Type"=>"application/json"
				]);
			}else{
				return new Response('Aucunes écritures trouves avec cet uuid',404);
			}

		}

		/**
		 * @Route("/api/post", name="api_post_Post", methods={"POST"})
         */
		public function create(Request $request)
	 	{
			 $jsonRecu = $request->getContent();

			 dd($jsonRecu);

	 	}
}
