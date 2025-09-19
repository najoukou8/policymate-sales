<?php

namespace App\Entity;

use App\Repository\ImportErrorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImportErrorRepository::class)
 */
class ImportError
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rowNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rowData;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRowNumber(): ?int
    {
        return $this->rowNumber;
    }

    public function setRowNumber(int $rowNumber): self
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getRowData(): ?string
    {
        return $this->rowData;
    }

    public function setRowData(string $rowData): self
    {
        $this->rowData = $rowData;

        return $this;
    }
}
