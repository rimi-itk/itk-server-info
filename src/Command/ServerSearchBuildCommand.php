<?php

namespace App\Command;

use App\DataProcessor\Server\SearchBuilder;
use App\Repository\ServerRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerSearchBuildCommand extends Command
{
    protected static $defaultName = 'app:server:search:build';

    private ServerRepository $serverRepository;

    private SearchBuilder $searchBuilder;

    public function __construct(ServerRepository $serverRepository, SearchBuilder $searchBuilder)
    {
        parent::__construct();
        $this->serverRepository = $serverRepository;
        $this->searchBuilder = $searchBuilder;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $servers = $this->serverRepository->findAll();
        foreach ($servers as $server) {
            $this->searchBuilder->build($server);
        }

        return Command::SUCCESS;
    }
}
