<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Api\User\RegisterController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

// https://api-platform.com/docs/core/operations/

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

#[ApiResource(

 collectionOperations: [ 'register' => [
                       'normalization_context' => [
                           'groups' => 'read:user:collection:register',
                       ],
                       'denormalization_context' => [
                           'groups' => 'write:user:collection:register',
                       ],
                       'method' => 'POST',
                       'path' => '/register',
                       'controller' => RegisterController::class,
                       'write' => false,
                   ],
               
                   'get' => ['normalization_context' => ['groups' => 'user:list', 'enable_max_depth'=> [true]],
                       ],
               
                    ],
 itemOperations: ['put','get' => ['normalization_context' => ['groups' => 'user:item']],
                    ],


)]

#[ApiFilter(SearchFilter::class, properties: ['roles' => 'partial', 'email' => 'exact' ], )]


class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:list', 'user:item'])]
    private $id;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:list', 'user:item', 'order:list'])]
    private $email;

    /**
     * @Groups({"user:read", "user:write"})
     */
    #[ORM\Column(type: 'json')]
    #[Groups(['user:list', 'user:item'])]
    private $roles = [];

    /**
     * @Groups({"user:write"})
     */
    #[ORM\Column(type: 'string')]
    #[Groups(['user:list', 'user:item'])]
    private $password;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['user:list', 'user:item', 'order:list'])]
    private $lastname;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['user:list', 'user:item', 'order:list'])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $address;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $city;

    use TimestampableEntity;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $phonenumber;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['user:list', 'user:item'])]
    private $status;

    /*#[ORM\OneToOne(inversedBy: 'user', targetEntity: PointOfSale::class, cascade: ['persist', 'remove'])]
    #[Groups(['user:list', 'user:item', 'order:list'])]
    private $pointofsale;*/

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Order::class)]
    private $orders;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $latitude;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $longitude;

    #[ORM\OneToMany(mappedBy: 'deliverer', targetEntity: Order::class)]
    private $ordersDeliverer;

    #[ORM\ManyToOne(targetEntity: PointOfSale::class, inversedBy: 'users')]
    #[Groups(['user:list', 'user:item', 'order:list'])]
    private $pointOfSale;

    #[ORM\OneToMany(mappedBy: 'deliveryman', targetEntity: Circuit::class)]
    private $circuits;

    public function __construct()
    {

         $this->orders = new ArrayCollection();
         $this->ordersDeliverer = new ArrayCollection();
         $this->circuits = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->firstname;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

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


    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

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
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }


    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrdersDeliverer(): Collection
    {
        return $this->ordersDeliverer;
    }

    public function addOrdersDeliverer(Order $ordersDeliverer): self
    {
        if (!$this->ordersDeliverer->contains($ordersDeliverer)) {
            $this->ordersDeliverer[] = $ordersDeliverer;
            $ordersDeliverer->setDeliverer($this);
        }

        return $this;
    }

    public function removeOrdersDeliverer(Order $ordersDeliverer): self
    {
        if ($this->ordersDeliverer->removeElement($ordersDeliverer)) {
            // set the owning side to null (unless already changed)
            if ($ordersDeliverer->getDeliverer() === $this) {
                $ordersDeliverer->setDeliverer(null);
            }
        }

        return $this;
    }


    public static function createFromPayload($username, array $payload)
    {
        $user = new User();
        $user->setEmail($username);

        return $user;
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

    /**
     * @return Collection<int, Circuit>
     */
    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuit $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits[] = $circuit;
            $circuit->setDeliveryman($this);
        }

        return $this;
    }

    public function removeCircuit(Circuit $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            // set the owning side to null (unless already changed)
            if ($circuit->getDeliveryman() === $this) {
                $circuit->setDeliveryman(null);
            }
        }

        return $this;
    }
}
