<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SaleRepository::class)
 */
class Sale
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $orderId;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customerEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productSku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $unitPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getOrderId(): string
    {
        return $this->orderId;
    }
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId; return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getProductSku(): ?string
    {
        return $this->productSku;
    }

    public function setProductSku(?string $productSku): self
    {
        $this->productSku = $productSku;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?string $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
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
}
