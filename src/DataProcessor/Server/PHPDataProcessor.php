<?php

namespace App\DataProcessor\Server;

class PHPDataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['php'])) {
            if (isset($serverData['php']['version'])) {
                $version = $serverData['php']['version'];
                if (is_string($version) && preg_match('@(?P<version>\d+(?:\.\d+){2})@', $version, $matches)) {
                    $data['php']['version'] = $matches['version'];
                }
            }
        }

        return $data;
    }
}
