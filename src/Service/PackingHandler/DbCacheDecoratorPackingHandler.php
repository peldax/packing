<?php

namespace App\Service\PackingHandler;

use App\Entity\Packaging;
use App\Entity\PackResultCache;
use App\Repository\PackagingRepository;
use App\Repository\PackResultCacheRepository;
use App\Service\Dto\InputBox;
use App\Service\Exception\NoSuitablePackagingFound;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

final readonly class DbCacheDecoratorPackingHandler implements PackingHandler
{
    public function __construct(
        private PackingHandler $innerHandler,
        private PackResultCacheRepository $packResultCacheRepository,
        private EntityManagerInterface $entityManager,
        private LockFactory $lockFactory,
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
        $cacheKey = $this->computeCacheKey($boxes);

        // avoid race condition using lock, TODO: figure out more effective way, current approach is too pessimistic
        $lock = $this->lockFactory->createLock('packing_handler_' . $cacheKey);

        try {
            $cache = $this->packResultCacheRepository->findOneBy(['cacheKey' => $cacheKey]);

            if ($cache instanceof PackResultCache) {
                return $cache->getResultPackaging();
            }

            $packaging = $this->innerHandler->handle($boxes);
            $resultCache = new PackResultCache($cacheKey, $packaging);
            $this->entityManager->persist($resultCache);
            $this->entityManager->flush();

            return $packaging;
        } finally {
            $lock->release();
        }
    }

    /**
     * @param list<InputBox> $boxes
     */
    public function computeCacheKey(array $boxes) : string
    {
        // Normalize list of boxes
        \usort($boxes, static function (InputBox $a, InputBox $b) : int {
            $xCompare = $a->x <=> $b->x; // TODO: think about making the function less branchy

            if ($xCompare !== 0) {
                return $xCompare;
            }

            $yCompare = $a->y <=> $b->y;

            if ($yCompare !== 0) {
                return $yCompare;
            }

            $zCompare = $a->z <=> $b->z;

            if ($zCompare !== 0) {
                return $zCompare;
            }

            return $a->weight <=> $b->weight;
        });

        return \md5(\serialize($boxes)); // TODO: make string conversion more sophisticated
    }
}
