<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'orderDetail:list', 'enable_max_depth'=> [true]]],
        'post' ,
    ],
)]

/*#[ApiFilter(SearchFilter::class, properties: ['command' => 'exact' ], )]*/

class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:list' ])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['order:list'])]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'ordersDetails')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['order:list'])]
    private $product;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    private $command;

    public function getSubtotal(): ?int
    {

        return $this->getQuantity() * $this->getProduct()->getPrice();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCommand(): ?Order
    {
        return $this->command;
    }

    public function setCommand(?Order $command): self
    {
        $this->command = $command;

        return $this;
    }
}
