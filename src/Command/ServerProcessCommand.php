<?php

namespace App\Command;

use App\Util\ServerHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class ServerProcessCommand extends Command
{
    protected static $defaultName = 'app:server:process';

    /** @var ServerHelper */
    private $serverHelper;

    public function __construct(ServerHelper $dataParser)
    {
        parent::__construct();
        $this->serverHelper = $dataParser;
    }

    protected function configure()
    {
        $this
            ->addArgument('server-name', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Servers to process')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force processing server data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serverNames = $input->getArgument('server-name');
        $logger = new ConsoleLogger($output);
        $this->serverHelper->setLogger($logger);
        $this->serverHelper->process($serverNames, [
            'force' => $input->getOption('force'),
        ]);

        return Command::SUCCESS;
    }
}
