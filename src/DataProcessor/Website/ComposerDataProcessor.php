<?php

namespace App\DataProcessor\Website;

use App\Util\DataParser;

class ComposerDataProcessor extends AbstractDataProcessor
{
    public function getData(array $websiteData): ?array
    {
        $data = [];

        foreach ($websiteData as $type => $info) {
            if (isset($info['composer-show']['@text'])) {
                $data['composer-show'] = DataParser::parseJson($info['composer-show']['@text']);
            }
        }

        return $data;
    }
}
