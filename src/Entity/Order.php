<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?float $totalPrice = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'purchaseOrder', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $items;
    /**
     * @var true
     */
    private bool $flagPriceIsDirty = false;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?float
    {
        if( $this->flagPriceIsDirty ) {
            $this->computeTotalPrice();
        }
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): static
    {
        $orderItem = $this->items->findFirst(function($key, $orderItem) use ($item) {
            return $orderItem->getProduct()->getId() === $item->getProduct()->getId();
        });
        if( $orderItem ) {
            $orderItem->setQuantity($orderItem->getQuantity() + $item->getQuantity());
        } else {
            $this->items->add($item);
            $item->setUnitPrice($item->getProduct()->getPrice());
            $item->setPurchaseOrder($this);
        }

        $this->flagPriceIsDirty = true;

        return $this;
    }


    public function removeItem(OrderItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPurchaseOrder() === $this) {
                $item->setPurchaseOrder(null);
            }
        }

        return $this;
    }

    public function addProduct(Product $product)
    {
        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(1);
        $this->addItem($item);
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function computeTotalPrice() : void
    {
        $totalPrice = 0;
        foreach($this->items as $item) {
            $totalPrice += $item->getQuantity() * $item->getUnitPrice();
        }
        $this->setTotalPrice($totalPrice);
    }
}
