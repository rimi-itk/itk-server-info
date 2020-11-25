<?php

namespace App\ServerDataProcessor;

class NginxDataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['nginx'])) {
            if (isset($serverData['nginx']['version'])) {
                $version = $serverData['nginx']['version'];
                if (is_string($version) && preg_match('@(?P<version>\d+(?:\.\d+){2})@', $version, $matches)) {
                    $data['nginx']['version'] = $matches['version'];
                }
            }
        }

        return $data;
    }
}
