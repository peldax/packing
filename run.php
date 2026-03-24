<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$symfonyFactory = new HttpFoundationFactory();
$psrFactory = new PsrHttpFactory();

$request = new ServerRequest('POST', new Uri('http://localhost/pack'), ['Content-Type' => 'application/json'], $argv[1]);
$response = new Kernel('dev', true)->handle($symfonyFactory->createRequest($request));

echo "<<< In:\n" . Message::toString($request) . "\n\n";
echo ">>> Out:\n" . Message::toString($psrFactory->createResponse($response)) . "\n\n";
