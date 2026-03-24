<?php

namespace App\OpenApi\Dto;

final readonly class PackOutput
{
    public function __construct(
        public OutputBox $box,
    )
    {
    }
}
