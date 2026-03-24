<?php

namespace App\Service\PackingHandler;

use App\Entity\Packaging;
use App\Repository\PackagingRepository;
use App\Service\Dto\InputBox;
use App\Service\Exception\NoSuitablePackagingFound;

final readonly class TrivialPackingHandler implements PackingHandler
{
    public function __construct(
        private PackagingRepository $packagingRepository,
    )
    {
    }

    /**
     * Returns smallest possible Packaging.
     *
     * @param list<InputBox> $boxes
     * @return Packaging
     */
    public function handle(array $boxes) : Packaging
    {
        $width = 0;
        $height = 0;
        $length = 0;
        $weight = 0;

        foreach ($boxes as $box) {
            $width = \max($width, $box->x);
            $height = \max($height, $box->y);
            $length += $box->z;
            $weight += $box->weight;
        }

        return $this->packagingRepository->findPackagingByDimensions($width, $height, $length, $weight)
            ?? throw new NoSuitablePackagingFound();
    }
}
