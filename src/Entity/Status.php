<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    const OPEN_ID      = 1;
    const CLOSE_ID     = 2;
    const CANCELLED_ID = 3;

    const OPEN_NAME      = 'Открыто';
    const CLOSE_NAME     = 'Закрыто';
    const CANCELLED_NAME = 'Отменено';

    const STATUSES = [
        self::OPEN_ID      => self::OPEN_NAME,
        self::CLOSE_ID     => self::CLOSE_NAME,
        self::CANCELLED_ID => self::CANCELLED_NAME
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
