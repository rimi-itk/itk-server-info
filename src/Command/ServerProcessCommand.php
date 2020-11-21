<?php

namespace App\Command;

use App\Helper\ServerHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class ServerProcessCommand extends Command
{
    protected static $defaultName = 'app:server:process';

    /** @var ServerHelper */
    private $serverHelper;

    public function __construct(ServerHelper $serverHelper)
    {
        parent::__construct();
        $this->serverHelper = $serverHelper;
    }

    protected function configure()
    {
        $this
            ->addArgument('server-name', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Servers to process')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serverNames = $input->getArgument('server-name');
        $logger = new ConsoleLogger($output);
        $this->serverHelper->setLogger($logger);
        $this->serverHelper->process($serverNames);

        return Command::SUCCESS;
    }
}
