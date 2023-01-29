<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('users/generate')]
    public function generateUsers(ManagerRegistry $doctrine):Response
    {
        $map = [
          'Василь' => rand(100, 999),
          'Петро' => rand(100, 999),
          'Андрій' => rand(100, 999),
          'Марина' => rand(100, 999),
          'Марія' => rand(100, 999),
          'Вікторія' => rand(100, 999),
        ];

        $entityManager = $doctrine->getManager();

        foreach ($map as $login => $pass) {
            $user = new User($login, $pass);
            $entityManager->persist($user);
        }

        $entityManager->flush();

        return new Response('Users are create');
    }
}