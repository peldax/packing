<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a box available in the warehouse.
 *
 * Warehouse workers pack a set of products for a given order into one of these boxes.
 */
#[ORM\Entity]
class PackResultCache
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $cacheKey;

    #[ORM\ManyToOne(targetEntity: Packaging::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Packaging $resultPackaging;

    public function __construct(string $key, Packaging $resultPackaging)
    {
        $this->cacheKey = $key;
        $this->resultPackaging = $resultPackaging;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getKey() : string
    {
        return $this->cacheKey;
    }

    public function getResultPackaging() : Packaging
    {
        return $this->resultPackaging;
    }
}
