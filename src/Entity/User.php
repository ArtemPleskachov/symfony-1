<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'users')]

class User
{
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_VIP = 2;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(length: 60)]
    private string $login;

    #[ORM\Column(length: 32)]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Phone::class, fetch: 'EAGER')]
    private Collection $phones;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $status = 0;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $age = null;

//    #[ORM\Column(type: Types::STRING, length: 42, nullable: true)]
//    private string|null $city;

    /**
     * @param string $login
     * @param string $password
     * @param int $status
     */
    public function __construct(string $login, string $password, int $status = self::STATUS_DISABLED)
    {
        $this->login = $login;
        $this->changePassword($password);
        $this->status = $status;
        $this->phones = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function changeLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function changePassword(string $password): void
    {
        $this->password = md5($password);
    }

    public function isActiveUser(): bool
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    public function isDisabledUser(): bool
    {
        return $this->status === static::STATUS_DISABLED;
    }

    public function isVIPUser(): bool
    {
        return $this->status === static::STATUS_VIP;
    }

    public function setStatusDisabled(): void
    {
        $this->status = static::STATUS_DISABLED;
    }

    public function setStatusActive(): void
    {
        $this->status = static::STATUS_ACTIVE;
    }

    public function setStatusVIP(): void
    {
        $this->status = static::STATUS_VIP;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @param Phone $phone
     */
    public function setPhones(Phone $phone): void
    {
        $this->phones->add($phone);
    }

//    /**
//     * @return string|null
//     */
//    public function getCity(): ?string
//    {
//        return $this->city;
//    }
//
//    /**
//     * @param string|null $city
//     */
//    public function setCity(?string $city): void
//    {
//        $this->city = $city;
//    }



}
