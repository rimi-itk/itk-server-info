<?php

namespace App\DataProcessor\Website;

use App\Entity\Website;

class Manager
{
    /** @var iterable|DataProcessorInterface[] */
    private $processors;

    public function __construct(iterable $websiteDataProcessors)
    {
        $this->processors = $websiteDataProcessors;
    }

    public function getType(array $serverData)
    {
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $type = $processor->getType($serverData);
            if (null !== $type) {
                return $type;
            }
        }

        return Website::TYPE_UNKNOWN;
    }

    public function getVersion(array $serverData)
    {
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $version = $processor->getVersion($serverData);
            if (null !== $version) {
                return $version;
            }
        }

        return Website::VERSION_UNKNOWN;
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
