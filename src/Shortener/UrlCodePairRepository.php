<?php

namespace App\Shortener;

use App\Entity\UrlCodePairEntity;
use App\Shortener\Exceptions\DataNotFoundException;
use App\Shortener\Interfaces\ICodeRepository;
use App\Shortener\ValueObjects\UrlCodePair;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class UrlCodePairRepository implements ICodeRepository
{
    protected ObjectRepository $cpRepository;
    protected ObjectManager $em;

    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->em = $this->doctrine->getManager();
        $this->cpRepository = $this->doctrine->getRepository(UrlCodePairEntity::class);
    }

    public function saveEntity(UrlCodePair $urlCodePairVO): bool
    {
        $codePair = new UrlCodePairEntity($urlCodePairVO->getUrl(), $urlCodePairVO->getCode());
        $this->em->persist($codePair);
        $this->em->flush();

        return true;
    }

    public function codeIsset(string $code): bool
    {
        return $this->cpRepository->findOneBy(['code' => $code]);
    }

    public function getUrlByCode(string $code): string
    {
        try {
        /**
         * @var UrlCodePairEntity $codePair;
         */
        $codePair = $this->cpRepository->findOneBy(['code' => $code]);
        return $codePair->getUrl();
        } catch (\Throwable) {
			throw new DataNotFoundException('Url not found by code');
        }
    }

    public function getCodeByUrl(string $url): string
    {
        try {
            /**
             * @var UrlCodePairEntity $codePair;
             */
            $codePair = $this->cpRepository->findOneBy(['url' => $url]);
            return $codePair->getCode();
        } catch (\Throwable) {
            throw new DataNotFoundException('Code not found by url');
        }

    }






}