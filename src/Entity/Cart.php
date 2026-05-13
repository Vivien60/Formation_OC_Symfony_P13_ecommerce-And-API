<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['cart:read'])]
    private Collection $items;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function emptyCart() : static
    {
        $this->items->clear();

        return $this;
    }

    public function setProductQuantityOrRemove(Product $product, int $newQuantity) : static
    {
        if($newQuantity < 1) {
            $this->removeProduct($product);
        } else {
            $this->changeProductQuantity($product, $newQuantity);
        }

        return $this;
    }

    public function removeProduct(Product $product) : static
    {
        $cartItem = $this->findItemFromProduct($product);
        $this->removeItem($cartItem);

        return $this;
    }

    /**
     * Méthode qui change la quantité d'un produit.
     * Si l'item/la ligne n'existe pas encore, il est créé
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    protected function changeProductQuantity(Product $product, int $quantity): void
    {
        $cartItem = $this->findItemFromProduct($product);
        if($cartItem) {
            $cartItem->setQuantity($quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $this->addItem($cartItem);
        }
    }

    public function addProduct(Product $product, int $quantity = 1) : static
    {
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $this->addItem($cartItem);

        return $this;
    }

    public function addItem(CartItem $item): static
    {
        $cartItem = $this->findItemFromProduct($item->getProduct());
        if( $cartItem ) {
            $cartItem->setQuantity($item->getQuantity());
        } else {
            $this->items->add($item);
            $item->setCart($this);
        }

        return $this;
    }

    public function removeItem(CartItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }


    protected function findItemFromProduct(Product $product): mixed
    {
        $cartItem = $this->items->findFirst(
            function ($key, $cartItem) use ($product) {
                return $cartItem->getProduct()->getId() === $product->getId();
            }
        );
        return $cartItem;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?float
    {
        $totalPrice = 0;
        foreach( $this->items as $item ) {
            $totalPrice += $item->getSubtotal();
        }
        return $totalPrice;
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

    /**
     * @return Collection<int, CartItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }
}
