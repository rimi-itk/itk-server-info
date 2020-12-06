<?php

namespace App\Command;

use App\DataProcessor\Website\SearchBuilder;
use App\Repository\WebsiteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsiteSearchBuildCommand extends Command
{
    protected static $defaultName = 'app:website:search:build';

    private WebsiteRepository $websiteRepository;

    private SearchBuilder $searchBuilder;

    public function __construct(WebsiteRepository $websiteRepository, SearchBuilder $searchBuilder)
    {
        parent::__construct();
        $this->websiteRepository = $websiteRepository;
        $this->searchBuilder = $searchBuilder;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $websites = $this->websiteRepository->findAll();
        foreach ($websites as $website) {
            $this->searchBuilder->build($website);
        }

        return Command::SUCCESS;
    }
}
