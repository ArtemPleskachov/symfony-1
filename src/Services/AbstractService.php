<?php

namespace App\Services;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;

abstract class AbstractService
{
    protected ObjectManager $entityManager;

    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->entityManager = $this->doctrine->getManager();
    }

    protected function save(object $object = null): void
    {
        if (!is_null($object)) {
            $this->entityManager->persist($object);
        }
        $this->entityManager->flush();
    }





}