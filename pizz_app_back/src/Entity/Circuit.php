<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CircuitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CircuitRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'circuit:list', 'enable_max_depth'=> [true]]],

    ],
    itemOperations: [ 'put', 'get' => ['normalization_context' => ['groups' => 'circuit:item']],
    ],
)]

#[ApiFilter(SearchFilter::class, properties: ['deliveryman' => 'exact'] )]

class Circuit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['circuit:list', 'circuit:item'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'circuits')]
    private $deliveryman;

    #[ORM\Column(type: 'json')]
    #[Groups(['circuit:list', 'circuit:item'])]
    private $coords = [];

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'boolean')]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryman(): ?User
    {
        return $this->deliveryman;
    }

    public function setDeliveryman(?User $deliveryman): self
    {
        $this->deliveryman = $deliveryman;

        return $this;
    }

    public function getCoords(): ?array
    {
        return $this->coords;
    }

    public function setCoords(array $coords): self
    {
        $this->coords = $coords;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
