<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    #[Route('/users/generate')]
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

    #[Route('/users')]
    public function getUsers(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        $result = '';
        foreach ($users as $user) {
            $result .= $user->getId()
                . ' - ' . $user->getLogin()
                . ' - ' . $user->getStatus()
                . '<br>';
        }
        return new Response(
            $result
        );
    }



    #[Route('/user/{id}/vip', requirements: ['id' => '\d+'], methods: ['GET'])]

    public function userVip(ManagerRegistry $doctrine, $id): Response
    {

        $user = $doctrine->getRepository(User::class)->find($id);
        $user->setStatusVIP();
        $doctrine->getManager()->flush();

        $result = 'User ' . $user->getId() . ' - ' .$user->getLogin() . ' - VIP';

        return new Response(
            $result
        );
    }






}