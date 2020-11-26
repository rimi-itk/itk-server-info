<?php

namespace App\DataProcessor\Server;

class Apache2DataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['apache2'])) {
            if (isset($serverData['apache2']['version'])) {
                $version = $serverData['apache2']['version'];
                if (is_string($version) && preg_match('@(?P<version>\d+(?:\.\d+){2})@', $version, $matches)) {
                    $data['apache']['version'] = $matches['version'];
                }
            }
        }

        return $data;
    }
}
