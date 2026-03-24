<?php

namespace App\Service;

use App\Entity\Packaging;
use App\Service\Dto\InputBox;
use App\Service\Exception\PrimaryHandlerFailure;
use App\Service\PackingHandler\PackingHandler;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class PackingService
{
    public function __construct(
        #[Autowire(service: 'primary_packing_handler')]
        private PackingHandler $primaryHandler,
        #[Autowire(service: 'fallback_packing_handler')]
        private PackingHandler $fallbackHandler,
    )
    {
    }

    /**
     * @param list<InputBox> $input
     */
    public function getSmallestPossiblePackaging(array $input) : Packaging
    {
        try {
            return $this->primaryHandler->handle($input);
        } catch (PrimaryHandlerFailure) {
            // nothing here
        }

        return $this->fallbackHandler->handle($input);
    }
}
