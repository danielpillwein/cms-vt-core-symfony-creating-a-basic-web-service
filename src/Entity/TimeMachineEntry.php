<?php

namespace App\Entity;

use App\Repository\TimeMachineEntryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeMachineEntryRepository::class)]
class TimeMachineEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $resourceURL = null;

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

    public function getResourceURL(): ?string
    {
        return $this->resourceURL;
    }

    public function setResourceURL(string $resourceURL): static
    {
        $this->resourceURL = $resourceURL;

        return $this;
    }
}
