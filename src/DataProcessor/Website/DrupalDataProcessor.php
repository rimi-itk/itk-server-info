<?php

namespace App\DataProcessor\Website;

use App\Entity\Website;

class DrupalDataProcessor implements DataProcessorInterface
{
    public function getType(array $data): ?string
    {
        return isset($serverData['drupal']) ? Website::TYPE_DRUPAL : null;
    }

    public function getVersion(array $data): ?string
    {
        if (isset($serverData['drupal']['version'])) {
            return $serverData['drupal']['version']['@text'];
        }

        return null;
    }

    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['drupal'])) {
        }

        return $data;
    }
}
