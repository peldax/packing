<?php

namespace App\Service\PackingHandler;

use App\Entity\Packaging;
use App\Repository\PackagingRepository;
use App\Service\Dto\InputBox;
use App\Service\Exception\PrimaryHandlerFailure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class ThreeDBinPackingPackingHandler implements PackingHandler
{
    public function __construct(
        #[Autowire(env: 'THREE_D_BIN_PACKING_URL')]
        private string $apiUrl,
        private Client $httpClient,
        private PackagingRepository $packagingRepository,
        private LoggerInterface $logger,
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
        $body = [
            'containers' => $this->fetchContainers(),
            'items' => $this->constructItems($boxes),
        ];

        try {
            $response = $this->httpClient->post($this->apiUrl, [
                RequestOptions::HEADERS => ['Content-Type' => 'application/json'],
                RequestOptions::BODY => \json_encode($body),
            ]);
            $responseBody = $response->getBody()->getContents();
            $packagingId = (int) json_decode($responseBody, true)['packedContainers'][0]['containerId']; // TODO: struktura odpovědi, validace pouze jednoho package a pod.
        } catch (ClientException $exception) {
            $this->logger->emergency($exception->getMessage(), ['exception' => $exception->getResponse()->getBody()->getContents()]);

            throw new PrimaryHandlerFailure();
        }

        return $this->packagingRepository->find($packagingId);
    }

    private function fetchContainers() : array
    {
        $return = [];

        foreach ($this->packagingRepository->findAll() as $packaging) {
            $return[] = [
                'id' => (string) $packaging->getId(),
                'width' => (int) \ceil($packaging->getWidth()),
                'depth' => (int) \ceil($packaging->getHeight()),
                'length' => (int) \ceil($packaging->getLength()),
                'maxWeight' => (int) \ceil($packaging->getMaxWeight()),
                // TODO: api má parametr empty weight, k čemu je, je to relevantní?
            ];
        }

        return $return;
    }

    /**
     * Returns smallest possible Packaging.
     *
     * @param list<InputBox> $boxes
     * @return list<array>
     */
    private function constructItems(array $boxes) : array
    {
        $return = [];

        foreach ($boxes as $item) {
            $return[] = [
                'id' => uniqid(),
                'width' => (int) \ceil($item->x),
                'depth' => (int) \ceil($item->y),
                'length' => (int) \ceil($item->z),
                'weight' => (int) \ceil($item->weight),
            ];
        }

        return $return;
    }
}
