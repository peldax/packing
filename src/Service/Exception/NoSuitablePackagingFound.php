<?php

namespace App\Service\Exception;

final class NoSuitablePackagingFound extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('No suitable Packing was found for given input.');
    }
}
