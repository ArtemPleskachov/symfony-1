<?php

namespace App\Controller;

use App\Shortener\Interfaces\IUrlEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/url')]
class ShortenerController extends AbstractController
{


    public function __construct(
		protected IUrlEncoder $encoder,
		protected IUrlEncoder $decoder
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
	
	

}