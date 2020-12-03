<?php

namespace App\DataProcessor\Website;

use App\Entity\Website;

class SymfonyDataProcessor implements DataProcessorInterface
{
    public function getType(array $data): ?string
    {
        return isset($serverData['symfony']) ? Website::TYPE_SYMFONY : null;
    }

    public function getVersion(array $data): ?string
    {
        if (isset($serverData['symfony']['version'])) {
            return $serverData['symfony']['version']['@text'];
        }

        return null;
    }

    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['symfony'])) {
        }

        return $data;
    }
}
