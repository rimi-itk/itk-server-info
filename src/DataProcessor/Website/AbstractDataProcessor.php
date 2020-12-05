<?php

namespace App\DataProcessor\Website;

class AbstractDataProcessor implements DataProcessorInterface
{
    public function getType(array $websiteData): ?string
    {
        return null;
    }

    public function getVersion(array $websiteData): ?string
    {
        return null;
    }

    public function getData(array $websiteData): ?array
    {
        return null;
    }
}
