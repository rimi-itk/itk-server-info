<?php

namespace App\DataProcessor\Server;

class ComposerDataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        foreach (['composer', 'composer1', 'composer2'] as $composer) {
            if (isset($serverData[$composer])) {
                if (isset($serverData[$composer]['version'])) {
                    $version = $serverData[$composer]['version'];
                    if (is_string($version) && preg_match('@(?P<version>\d+(?:\.\d+){2})@', $version, $matches)) {
                        $data[$composer]['version'] = $matches['version'];
                    }
                }
            }
        }

        return $data;
    }
}
