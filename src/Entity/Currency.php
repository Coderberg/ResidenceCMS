<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EntityIdTrait;
use App\Repository\CurrencyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    use EntityIdTrait;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private ?string $currency_title = null;

    #[ORM\Column(type: Types::STRING, length: 3)]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true)]
    private ?string $symbol_left = null;

    #[ORM\Column(type: Types::STRING, length: 12, nullable: true)]
    private ?string $symbol_right = null;

    public function getCurrencyTitle(): ?string
    {
        return $this->currency_title;
    }

    public function setCurrencyTitle(string $currency_title): self
    {
        $this->currency_title = $currency_title;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getSymbolLeft(): ?string
    {
        return $this->symbol_left;
    }

    public function setSymbolLeft(?string $symbol_left): self
    {
        $this->symbol_left = $symbol_left;

        return $this;
    }

    public function getSymbolRight(): ?string
    {
        return $this->symbol_right;
    }

    public function setSymbolRight(?string $symbol_right): self
    {
        $this->symbol_right = $symbol_right;

        return $this;
    }
}
