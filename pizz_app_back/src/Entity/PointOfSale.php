<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PointOfSaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: PointOfSaleRepository::class)]

#[ApiResource(

)]
class PointOfSale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['order:list', 'order:item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[MaxDepth(2)]
    #[Groups(['user:list','order:list', 'order:item'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:list', 'order:item'])]
    private $address;

    #[ORM\Column(type: 'string', length: 5)]
    #[Groups(['order:list'])]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:list'])]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:list', 'order:item'])]
    private $latitude;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:list', 'order:item'])]
    private $longitude;

    #[ORM\OneToMany(mappedBy: 'pointOfSale', targetEntity: Order::class)]
    private $orders;

    /*#[ORM\OneToOne(mappedBy: 'pointofsale', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private $user;*/

    #[ORM\OneToMany(mappedBy: 'product_pointofsale', targetEntity: Product::class)]
    private $products;

    #[ORM\OneToMany(mappedBy: 'pointOfSale', targetEntity: User::class)]
    private $users;

     public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->users = new ArrayCollection();

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setPointOfSale($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPointOfSale() === $this) {
                $order->setPointOfSale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProductPointofsale($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductPointofsale() === $this) {
                $product->setProductPointofsale(null);
            }
        }

        return $this;
    }

   /**
    * @return Collection<int, User>
    */
   public function getUsers(): Collection
   {
       return $this->users;
   }

   public function addUser(User $user): self
   {
       if (!$this->users->contains($user)) {
           $this->users[] = $user;
           $user->setPointOfSale($this);
       }

       return $this;
   }

   public function removeUser(User $user): self
   {
       if ($this->users->removeElement($user)) {
           // set the owning side to null (unless already changed)
           if ($user->getPointOfSale() === $this) {
               $user->setPointOfSale(null);
           }
       }

       return $this;
   }

}
