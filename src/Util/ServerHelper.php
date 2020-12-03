<?php

namespace App\Util;

use App\DataProcessor\Server\Manager as ServerDataProcessorManager;
use App\Entity\Server;
use App\Exception\InvalidDataException;
use App\Repository\ServerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /** @var ServerDataProcessorManager */
    private $serverDataProcessorManager;

    /** @var WebsiteHelper */
    private $websiteHelper;

    public function __construct(EntityManagerInterface $entityManager, DataParser $dataHelper, ServerDataProcessorManager $serverDataProcessorManager, WebsiteHelper $websiteHelper)
    {
        $this->entityManager = $entityManager;
        $this->dataHelper = $dataHelper;
        $this->serverDataProcessorManager = $serverDataProcessorManager;
        $this->websiteHelper = $websiteHelper;
    }

    public function process(array $serverNames, array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureProcessOptions($resolver);
        $options = $resolver->resolve($options);

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('s')
            ->from(Server::class, 's');
        if (!$options['force']) {
            $queryBuilder->andWhere('s.processedAt IS NULL OR s.processedAt < s.updatedAt');
        }
        if (!empty($serverNames)) {
            $queryBuilder->andWhere('s.name IN (:serverNames)');
            $queryBuilder->setParameter('serverNames', $serverNames);
        }

        $now = new DateTimeImmutable();
        $servers = $queryBuilder->getQuery()->execute();
        foreach ($servers as $server) {
            try {
                $this->processServer($server);
                $server->setProcessedAt($now);
                $this->entityManager->persist($server);
                $this->entityManager->flush();
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
    }

    public function log($level, $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    private function processServer(Server $server)
    {
        $this->info(sprintf('Processing server %s', $server->getName()));
        try {
            $serverData = $this->dataHelper->parseData($server->getRawData());
            $data = $this->serverDataProcessorManager->process($serverData);
            $server->setData($data);
            $this->websiteHelper->buildWebsites($server, $serverData);
        } catch (InvalidDataException $exception) {
            $this->error($exception->getMessage());
        }
    }

    private function configureProcessOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'force' => false,
        ]);
    }
}
