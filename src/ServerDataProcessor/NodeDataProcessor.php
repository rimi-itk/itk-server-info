<?php

namespace App\ServerDataProcessor;

class NodeDataProcessor implements DataProcessorInterface
{
    public function getData(array $serverData): ?array
    {
        $data = [];

        if (isset($serverData['node'])) {
            if (isset($serverData['node']['version'])) {
                $version = $serverData['node']['version'];
                if (is_string($version) && preg_match('/v(?P<version>\d+(?:\.\d+){2})/i', $version, $matches)) {
                    $data['node']['version'] = $matches['version'];
                }
            }
        }

        return $data;
    }
}
