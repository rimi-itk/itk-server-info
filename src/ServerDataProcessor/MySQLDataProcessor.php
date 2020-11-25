<?php

namespace App\ServerDataProcessor;

class MySQLDataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['mysql'])) {
            if (isset($serverData['mysql']['version'])) {
                $version = $serverData['mysql']['version'];
                if (is_string($version) && preg_match('@(?P<version>\d+(?:\.\d+){2}(?:-[a-z]+)?)@i', $version, $matches)) {
                    $data['mysql']['version'] = $matches['version'];
                }
            }
        }

        return $data;
    }
}
