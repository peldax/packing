<?php

namespace App\Service\PackingHandler;

use App\Entity\Packaging;
use App\Service\Dto\InputBox;

interface PackingHandler
{
    /**
     * Returns smallest possible Packaging.
     *
     * @param list<InputBox> $boxes
     * @return Packaging
     */
    public function handle(array $boxes) : Packaging;
}
