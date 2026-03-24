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
#[ORM\Index(columns: ['width', 'height', 'length', 'max_weight'])]
class Packaging
{

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::FLOAT)] // TODO: proč floaty?
    private float $width;

    #[ORM\Column(type: Types::FLOAT)]
    private float $height;

    #[ORM\Column(type: Types::FLOAT)]
    private float $length;

    #[ORM\Column(type: Types::FLOAT)]
    private float $volume;

    #[ORM\Column(type: Types::FLOAT)]
    private float $maxWeight;

    public function __construct(float $width, float $height, float $length, float $maxWeight)
    {
        if ($width <= 0 || $height <= 0 || $length <= 0 || $maxWeight <= 0) {
            throw new \InvalidArgumentException();
        }

        $arrayToSort = [$width, $height, $length];
        \sort($arrayToSort);

        [$this->width, $this->height, $this->length] = $arrayToSort; // TODO: duplicated logic from App\Service\Dto\InputBox

        $this->volume = $width * $height * $length;
        $this->maxWeight = $maxWeight;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getWidth() : float
    {
        return $this->width;
    }

    public function getHeight() : float
    {
        return $this->height;
    }

    public function getLength() : float
    {
        return $this->length;
    }

    public function getMaxWeight() : float
    {
        return $this->maxWeight;
    }
}
