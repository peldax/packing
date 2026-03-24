<?php

namespace App\Service\Exception;

final class PrimaryHandlerFailure extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Primary handler failed.');
    }
}
