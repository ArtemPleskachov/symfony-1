<?php

namespace App\Controller;

use App\Shortener\Interfaces\IUrlEncoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
	
		public function __construct(
		protected IUrlEncoder $converter,
		protected IUrlEncoder $anywayConverter
	)
	{
	}
	
	#[Route('/')]
    public function mainAction(): Response
    {
        return new Response('Hello World');
    }





}