<?php

namespace App\OpenApi\Dto;

use Symfony\Component\Validator\Constraints\Positive;

final readonly class InputBox
{
    public function __construct(
        public int|string $id,
        #[Positive]
        public float $width,
        #[Positive]
        public float $height,
        #[Positive]
        public float $length,
        #[Positive]
        public float $weight,
    )
    {
    }
}
