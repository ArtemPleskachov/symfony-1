<?php

namespace App\Services;


use App\Entity\UrlCodePairEntity;
use App\Repository\UrlRepository;
use App\Shortener\Exceptions\DataNotFoundException;
use Doctrine\Persistence\ObjectRepository;

class UrlService extends AbstractService
{
	/**
	 * @var UrlRepository
	 */
	protected ObjectRepository $repository;
	
	protected function init()
	{
		parent::init();
		$this->repository = $this->doctrine->getRepository(UrlCodePairEntity::class);
	}
	
	public function incrementUrlCounter(UrlCodePairEntity $url): static
	{
		$url->incrementCounter();
		$this->save($url);
		
		return $this;
	}
	
	/**
	 * @throws DataNotFoundException
	 */
	public function getUrlByCodeAndIncrement(string $code): UrlCodePairEntity
	{
		try {
			$url = $this->getUrlByCode($code);
			$url->incrementCounter();
			$this->save();
			return $url;
		} catch (\Throwable) {
			throw new DataNotFoundException('Url not found by code');
		}
	}
	
	/**
	 * @throws DataNotFoundException
	 */
	public function getUrlByCode(string $code): UrlCodePairEntity
	{
		try {
			return $this->repository->findOneBy(['code' => $code]);
		} catch (\Throwable) {
			throw new DataNotFoundException('Url not found by code');
		}
	}
	
	
	
	
	
}