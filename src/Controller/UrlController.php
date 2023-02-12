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

    #[Route('/encode', methods: 'POST')]
    public function encodeAction(Request $request): Response
    {
        $code = $this->encoder->encode($request->request->get('url'));
        return new Response($code);
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
			$response = new RedirectResponse($url->getUrl());
		} catch (\Throwable $e) {
			$response = new Response($e->getMessage(), 400);
		}
		return $response;
    }
	
	
	
	
	

}