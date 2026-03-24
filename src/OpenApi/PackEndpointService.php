<?php

namespace App\OpenApi;

use App\Entity\Packaging;
use App\OpenApi\Dto\OutputBox;
use App\OpenApi\Dto\PackInput;
use App\OpenApi\Dto\PackOutput;
use App\Service\Dto\InputBox;
use App\Service\Exception\NoSuitablePackagingFound;
use App\Service\PackingService;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class PackEndpointService
{
    public function __construct(
        private PackingService $packingService,
    )
    {
    }

    public function pack(PackInput $input) : PackOutput
    {
        try {
            $packaging = $this->packingService->getSmallestPossiblePackaging(self::mapServiceInput($input));
        } catch (NoSuitablePackagingFound) {
            throw new UnprocessableEntityHttpException('No suitable packaging found.');
        }

        return self::mapServiceOutput($packaging);
    }

    /**
     * @param PackInput $input
     * @return list<InputBox>
     */
    private static function mapServiceInput(PackInput $input) : array
    {
        $return = [];

        foreach ($input->products as $product) {
            $return[] = new InputBox($product->width, $product->height, $product->length, $product->weight);
        }

        return $return;
    }

    /**
     * @param Packaging $packaging
     * @return PackOutput
     */
    private static function mapServiceOutput(Packaging $packaging) : PackOutput
    {
        return new PackOutput(
            new OutputBox($packaging->getWidth(), $packaging->getHeight(), $packaging->getLength()),
        );
    }
}
