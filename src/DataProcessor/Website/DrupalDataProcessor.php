<?php

namespace App\DataProcessor\Website;

use App\Entity\Website;
use App\Util\DataParser;

class DrupalDataProcessor extends AbstractDataProcessor
{
    public function getType(array $websiteData): ?string
    {
        return isset($websiteData['drupal']) ? Website::TYPE_DRUPAL : null;
    }

    public function getVersion(array $websiteData): ?string
    {
        if (isset($websiteData['drupal']['drush-status']['@text'])) {
            try {
                $data = json_decode($websiteData['drupal']['drush-status']['@text'], true, 512, JSON_THROW_ON_ERROR);

                return $data['drupal-version'] ?? null;
            } catch (\JsonException $exception) {
            }
        }

        return null;
    }

    public function getData(array $websiteData): ?array
    {
        $data = [];

        if (isset($websiteData['drupal']['drush-modules']['@text'])) {
            $modules = DataParser::parseJson($websiteData['drupal']['drush-modules']['@text']);
            if ($modules) {
                $grouped = [
                    // We want to have enabled modules at the front.
                    'Enabled' => [],
                ];
                foreach ($modules as $module) {
                    $grouped[$module['status']][] = $module;
                }
                $grouped = array_filter($grouped);
                if ($grouped) {
                    $data['drush-modules'] = $grouped;
                }
            }
        }

        return $data;
    }
}
