<?php

namespace App\Util;

use JsonException;
use SimpleXMLElement;

class DataParser
{
    public function parseData(string $data): array
    {
        return $this->buildData($data);
    }

    public static function parseJson(string $json): ?array
    {
        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            return null;
        }
    }

    private function buildData(string $payload, int $level = 0): array
    {
        $sxe = new SimpleXMLElement($payload, LIBXML_NOCDATA);

        return $this->xml2array($sxe);

        return json_decode(json_encode($sxe), true);
    }

    /**
     * @return array|string
     */
    private function xml2array(SimpleXMLElement $sxe)
    {
        $array = [];

        foreach ($sxe->attributes() as $name => $value) {
            $array['@attributes'][$name] = (string) $value;
        }

        $text = (string) $sxe;
        if (!empty(trim($text))) {
            $array['@text'] = $text;
        }

        $children = [];
        foreach ($sxe->children() as $name => $child) {
            $children[$name][] = $this->xml2array($child);
        }
        $children = array_map(static fn ($list) => 1 === count($list) ? $list[0] : $list, $children);

        $array += $children;

        if (['@text'] === array_keys($array)) {
            return $array['@text'];
        }

        return $array;
    }
}
