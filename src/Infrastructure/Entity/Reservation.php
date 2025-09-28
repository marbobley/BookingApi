<?php

namespace App\Infrastructure\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\ReservationModel;
use App\Infrastructure\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource]
#[Map(source: ReservationModel::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Map(if: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Map(source: 'username')]
    private ?string $username = null;

    #[ORM\Column]
    #[Map(source: 'startingDate')]
    private ?\DateTimeImmutable $startingDate = null;

    #[ORM\Column]
    #[Map(source: 'minuteDuration')]
    private ?int $minuteDuration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getStartingDate(): ?\DateTimeImmutable
    {
        return $this->startingDate;
    }
    
    public function getMinuteDuration(): ?int
    {
        return $this->minuteDuration;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function setStartingDate(\DateTimeImmutable $startingDate): static
    {
        $this->startingDate = $startingDate;

        return $this;
    }


    public function setMinuteDuration(int $minuteDuration): static
    {
        $this->minuteDuration = $minuteDuration;

        return $this;
    }
}
