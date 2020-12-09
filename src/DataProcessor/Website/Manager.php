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

    public function getType(array $websiteData)
    {
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $type = $processor->getType($websiteData);
            if (null !== $type) {
                return $type;
            }
        }

        return Website::TYPE_UNKNOWN;
    }

    public function getVersion(array $websiteData)
    {
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $version = $processor->getVersion($websiteData);
            if (null !== $version) {
                return $version;
            }
        }

        return Website::VERSION_UNKNOWN;
    }

    public function getData(array $websiteData)
    {
        $data = [];
        $processors = $this->getProcessors();
        foreach ($processors as $processor) {
            $processedData = $processor->getData($websiteData);
            if (null !== $processedData) {
                $data[] = $processedData;
            }
        }

        return array_merge(...$data);
    }

    /**
     * @return iterable|DataProcessorInterface[]
     */
    public function getProcessors(): iterable
    {
        return $this->processors;
    }
}
