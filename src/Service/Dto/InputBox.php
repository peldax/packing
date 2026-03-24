<?php

namespace App\Service\Dto;

/**
 * This class self-normalizes dimensions into XYZ coordinates which are pre-ordered.
 */
final readonly class InputBox
{
    public float $x;
    public float $y;
    public float $z;

    public function __construct(
        float $width,
        float $height,
        float $length,
        public float $weight,
    )
    {
        if ($width <= 0 || $height <= 0 || $length <= 0 || $weight <= 0) {
            throw new \InvalidArgumentException();
        }

        $arrayToSort = [$width, $height, $length];
        \sort($arrayToSort);

        [$this->x, $this->y, $this->z] = $arrayToSort;
    }
}
