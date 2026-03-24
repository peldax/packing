<?php

namespace App\OpenApi\Dto;

final readonly class PackInput
{
    public function __construct(
        /** @var list<InputBox> */
        public array $products,
    )
    {
    }
}
