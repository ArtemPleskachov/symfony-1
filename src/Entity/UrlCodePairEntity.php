<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shortener\ValueObjects;



#[ORM\Entity(repositoryClass: UrlRepository::class)]
#[ORM\Table(name: 'url_code')]
class UrlCodePairEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;


    #[ORM\Column(type: Types::STRING, length: 246)]
    private string $url;

    #[ORM\Column(type: Types::STRING, length: 12)]
    private string $code;
	
	#[ORM\Column(type: Types::INTEGER)]
	private int $counter = 0;
	
	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	private \DateTime $createdAt;
	
	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	private \DateTime $lastVisit;
	
	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, updatable: true)]
	private \DateTime $updatedAt;
	
	

    /**
     * @param string $url
     * @param string $code
     */
    public function __construct(string $url, string $code)
    {
        $this->url = $url;
        $this->code = $code;
		$this->setCreatedAt();
		$this->updateDateTime();
		$this->lastVisit = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
	
	/**
	 * @return int
	 */
	public function getCounter(): int
	{
		return $this->counter;
	}
	
	/**
	 * @param int $counter
	 */
	public function incrementCounter(): void
	{
		$this->counter++;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}
	
	protected function setCreatedAt(): void
	{
		$this->createdAt = new \DateTime();
	}
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt(): \DateTime
	{
		return $this->updatedAt;
	}
	
	public function updateDateTime(): void
	{
		$this->updatedAt = new \DateTime();
	}
	
	/**
	 * @return \DateTime|null
	 */
	public function getLastVisit(): ?\DateTime
	{
		return $this->lastVisit;
	}
	
	/**
	 * @param \DateTime $lastVisit
	 */
	public function setLastVisit(\DateTime $lastVisit): self
	{
		$this->lastVisit = $lastVisit;
		
		return $this;
	}
	
	
	
	



}