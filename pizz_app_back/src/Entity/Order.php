<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]

#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'order:list', 'enable_max_depth'=> [true]]],
        'post' ,
    ],
    itemOperations: ['put','get' => ['normalization_context' => ['groups' => 'order:item','enable_max_depth'=> [true]]]]
)]
#[ApiFilter(SearchFilter::class, properties: ['customer' => 'exact', 'reference'=> 'partial' ])]

class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:list', 'order:item'])]
    private $id;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'string', length: 100, unique:true)]
    #[Groups(['order:list', 'order:item'])]
    private $reference;

    use TimestampableEntity;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\ManyToOne(targetEntity: PointOfSale::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:list', 'order:item'])]
    private $pointOfSale;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[Groups(['order:list', 'order:item'])]
    private $customer;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:list', 'order:item'])]
    private $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ordersDeliverer')]
    private $deliverer;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: OrderDetail::class)]
/*    #[ApiSubresource(
        maxDepth: 2
    )]*/
    #[MaxDepth(2)]
    #[Groups(['order:list', 'order:item'])]
    private $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }


public function __toString(): string
{
    return $this->reference;
}

    public function getTotal(): ?int
    {
        $total = 0;
        foreach($this->orderDetails as $item)
        {
         $total += $item->getSubTotal();
        }
        return $total;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }



    public function getPointOfSale(): ?PointOfSale
    {
        return $this->pointOfSale;
    }

    public function setPointOfSale(?PointOfSale $pointOfSale): self
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }


    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDeliverer(): ?User
    {
        return $this->deliverer;
    }

    public function setDeliverer(?User $deliverer): self
    {
        $this->deliverer = $deliverer;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setCommand($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getCommand() === $this) {
                $orderDetail->setCommand(null);
            }
        }

        return $this;
    }


}
