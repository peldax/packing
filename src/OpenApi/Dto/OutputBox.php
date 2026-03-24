<?php

namespace App\OpenApi\Dto;

final readonly class OutputBox
{
    public function __construct(
        public float $weight,
        public float $height,
        public float $length,
    )
    {
    }
}
