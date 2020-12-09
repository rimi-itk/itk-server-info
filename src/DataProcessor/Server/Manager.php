<?php

namespace App\DataProcessor\Server;

class Manager
{
    /** @var iterable|DataProcessorInterface[] */
    private $processors;

    public function __construct(iterable $serverDataProcessors)
    {
        $this->processors = $serverDataProcessors;
    }

    public function process(array $serverData)
    {
        $data = [];
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $processedData = $processor->getData($serverData);
            if (null !== $processedData) {
                $data = array_merge($data, $processedData);
            }
        }

        return $data;
    }

    /**
     * @return iterable|DataProcessorInterface[]
     */
    public function getProcessors(): iterable
    {
        return $this->processors;
    }
}
