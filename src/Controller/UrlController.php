<?php

namespace App\Controller;

use App\Entity\UrlCodePairEntity;
use App\Services\AbstractService;
use App\Services\UrlService;
use App\Shortener\Interfaces\IUrlDecoder;
use App\Shortener\Interfaces\IUrlEncoder;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/url')]
class UrlController extends AbstractController
{
	/**
	 * @param IUrlEncoder $encoder
	 * @param IUrlEncoder $decoder
	 * @param UrlService $urlService
	 */
	
    public function __construct(
		protected IUrlEncoder $encoder,
		protected IUrlEncoder $decoder,
		protected AbstractService $urlService
	)
	{
	}

    #[Route('/encode', name: 'url_encode', methods: 'POST')]
    public function encodeAction(Request $request): Response
    {
		try {
			$code = $this->encoder->encode($request->request->get('url'));
			return $this->redirectToRoute('new_redirect', ['code' => $code]);
		} catch (\Throwable $e) {
			return new Response($e->getMessage(), 400);
		}
		
    }
	
	#[Route('/decode', methods: 'POST')]
    public function decodeAction(Request $request): Response
    {
        $code = $this->decoder->decode($request->request->get('url'));
        return new Response($code);
    }
	
	#[Route('/{code}', requirements: ['code' => '\w{6}'], methods: ['get'])]
	public function redirectAction(string $code): Response
	{
		try {
			$url = $this->urlService->getUrlByCodeAndIncrement($code);
			$url->updateDateTime();
			$url->setLastVisit(new \DateTime());
			$response = new RedirectResponse($url->getUrl());
		} catch (\Throwable $e) {
			$response = new Response($e->getMessage(), 400);
		}
		return $response;
    }
	
	#[Route('/{code}/stat', requirements: ['code' => '\w{6}'], methods: ['get'])]
	public function redirectStatAction(string $code): Response
	{
		try {
			$url = $this->urlService->getUrlByCode($code);
			$response = new Response(
				'Id' . ' - ' . $url->getId() . '</br>'
			. 'Code' . ' - ' . $url->getCode() . '</br>'
			. 'Url' . ' - ' . $url->getUrl() . '</br>'
			. 'Counter' . ' - ' . $url->getCounter() . '</br>'
			. 'Created' . ' - ' . $url->getCreatedAt()->format('Y-m-d H:i:s') . '</br>'
			. 'Last Visit' . ' - ' . $url->getLastVisit()->format('Y-m-d H:i:s') . '</br>'
			);
		} catch (\Throwable $e) {
			$response = new Response($e->getMessage(), 400);
		}
		return $response;
    }
	
	#[Route('/code/new', methods: 'GET')]
	public function newUrlAction (Request $request): Response
	{
		return $this->render('new.html.twig');
	}
	
//	#[Route('/url/code/code/{code}/stat', name: 'new_redirect', requirements: ['code' => '\w{6}'], methods: ['GET'])]
//	public function redirectNewStatAction(string $code): Response
//	{
//		try {
//			$url = $this->urlService->getUrlByCode($code);
//			return $this->render('newstat.html.twig', [
//				'url' => $url,
//				'code' => $code,
////				'created' => $url->getCreatedAt()->format('Y-m-d H:i:s'),
////				'lastVisit' => $url->getLastVisit()->format('Y-m-d H:i:s')
//			]);
//		} catch (\Throwable $e) {
//			return new Response($e->getMessage(), 400);
//		}
//	}

	#[Route('/code/{code}/stat', name: 'new_redirect', requirements: ['code' => '\w{6}'], methods: ['GET'])]
	public function redirectNewStatAction(string $code): Response
	{
		$vars = [
			'code' => $code,
		];

		try {
			$url = $this->urlService->getUrlByCode($code);
			$response = new Response(
				$url->getId() . '</br>'
				. ' - ' . $url->getCode() . '</br>'
				. ' - ' . $url->getUrl() . '</br>'
				. ' - ' . $url->getCounter() . '</br>'
				. ' - ' . $url->getCreatedAt()->format('Y-m-d H:i:s') . '</br>'
				. ' - ' . $url->getLastVisit()->format('Y-m-d H:i:s') . '</br>'
			);

			$vars = $vars + [
				'url_info' => $response
			];


		} catch (\Throwable $e) {
			$response = new Response($e->getMessage(), 400);
			$vars = $vars +  [
				'error' => $e,
			];
		}
		return $this->render('url_statistic.html.twig', $vars);

	}
	
}