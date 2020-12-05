<?php

namespace App\DataProcessor\Website;

use App\Entity\Website;

class SymfonyDataProcessor extends AbstractDataProcessor
{
    public function getType(array $websiteData): ?string
    {
        return isset($websiteData['symfony']) ? Website::TYPE_SYMFONY : null;
    }

    public function getVersion(array $websiteData): ?string
    {
        if (isset($websiteData['symfony']['version']['@text'])
            && preg_match('/symfony\s+(?<version>\S+)/i', $websiteData['symfony']['version']['@text'], $matches)) {
            return $matches['version'];
        }

        return null;
    }
}
