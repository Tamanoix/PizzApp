<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Trait\SlugTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
/*#[ApiResource(
    collectionOperations: [
        'get' => ['normalization_context' => ['groups' => 'product:list', 'enable_max_depth'=> [true]]],
        'post' ,
    ],
)]*/
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'product:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'product:item']]],
    paginationEnabled: false,
)]
class Product
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product:list', 'order:list'])]
    private $id;

    #[ORM\Column(type: 'string', length: 200)]
  /*  #[Groups(['order:list'])]*/
    #[Groups(['product:list', 'order:list'])]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['product:list'])]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Groups(['product:list', 'order:list'])]
    private $price;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(2)]
    #[Groups(['product:list'])]
    private $category;

    use TimestampableEntity;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetail::class)]
    private $ordersDetails;

    #[ORM\ManyToOne(targetEntity: PointOfSale::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $product_pointofsale;

    public function __construct()
    {
        $this->ordersDetails = new ArrayCollection();

    }
    
    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrderDetail $ordersDetail): self
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails[] = $ordersDetail;
            $ordersDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrderDetail $ordersDetail): self
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getProduct() === $this) {
                $ordersDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getProductPointofsale(): ?PointOfSale
    {
        return $this->product_pointofsale;
    }

    public function setProductPointofsale(?PointOfSale $product_pointofsale): self
    {
        $this->product_pointofsale = $product_pointofsale;

        return $this;
    }



}
