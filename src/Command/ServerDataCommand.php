<?php

namespace App\Command;

use App\Repository\ServerRepository;
use App\Util\DataParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ServerDataCommand extends Command
{
    protected static $defaultName = 'app:server:data';

    private ServerRepository $serverRepository;

    private DataParser $dataParser;

    private SerializerInterface $serializer;

    public function __construct(ServerRepository $serverRepository, DataParser $dataParser, SerializerInterface $serializer)
    {
        parent::__construct();
        $this->serverRepository = $serverRepository;
        $this->dataParser = $dataParser;
        $this->serializer = $serializer;
    }

    protected function configure()
    {
        $this
            ->addArgument('server-name', InputArgument::REQUIRED, 'Server name')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The format', 'json')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serverName = $input->getArgument('server-name');
        $format = $input->getOption('format');

        $server = $this->serverRepository->find($serverName);
        if (null === $server) {
            throw new RuntimeException(sprintf('Invalid server name: %s', $serverName));
        }
        $data = $this->dataParser->parseData($server->getRawData());

        $output->writeln($this->serializer->serialize($data, $format));

        return Command::SUCCESS;
    }
}
