<?php

namespace App\Util;

use App\Entity\Server;
use App\Exception\InvalidDataException;
use App\Repository\ServerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;

class ServerHelper
{
    use LoggerTrait;
    use LoggerAwareTrait;

    /** @var ServerRepository */
    private $serverRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var DataParser */
    private $dataHelper;

    public function __construct(EntityManagerInterface $entityManager, DataParser $dataHelper)
    {
        $this->entityManager = $entityManager;
        $this->dataHelper = $dataHelper;
    }

    public function process(array $serverNames)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Server::class, 's')
            ->where('s.processedAt IS NULL OR s.processedAt < s.updatedAt');
        if (!empty($serverNames)) {
            $queryBuilder->andWhere('s.name IN (:serverNames)');
            $queryBuilder->setParameter('serverNames', $serverNames);
        }

        $servers = $queryBuilder->getQuery()->execute();
        foreach ($servers as $server) {
            $this->processServer($server);
        }
    }

    public function log($level, $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    private function processServer(Server $server) {
        $this->info(sprintf('Processing server %s', $server->getName()));
        try {
            $data = $this->dataHelper->parseData($server->getRawData());
            var_export($data);
        } catch (InvalidDataException $exception) {
            $this->error($exception->getMessage());
        }
    }
}
