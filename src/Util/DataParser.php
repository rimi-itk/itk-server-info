<?php

namespace App\Util;

use App\Exception\InvalidDataException;

class DataParser
{
    public function parseData(string $data): array
    {
        return $this->buildData($data);
    }

    private function buildData(string $payload, int $level = 0): array
    {
        $data = [];
        [$value, $blocks] = $this->getBlocks($payload, $level);
        if (null !== $value) {
            $data['@value'] = trim($value).PHP_EOL;
        }
        foreach ($blocks as [$start, $content, $end]) {
            $start = $this->getMetadata($start);
            $end = $this->getMetadata($end);
            $name = $start['name'];
            if ($name !== $end['name']) {
                throw new InvalidDataException(sprintf('Name mismatch: %s vs. %s', $name, $end['name']));
            }
            if (isset($start['@attributes'])) {
                $data[$name]['@attributes'] = $start['@attributes'];
            }
            $children = $this->buildData($content, $level + 1);
            foreach ($children as $key => $value) {
                $data[$name][$key] = $value;
            }
        }

        return $data;
    }

    /**
     * @return array
     *               [$content, $blocks]
     */
    private function getBlocks(string $data, int $level = 0, string $delimiter = '---'): array
    {
        $chunks = preg_split(
            '/^'.preg_quote(str_repeat($delimiter, $level + 1), '/').'\s(?P<metadata>.+)$/m',
            rtrim($data),
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );

        $value = null;
        // First chuck may be content
        if (1 === count($chunks) % 3) {
            $value = array_shift($chunks);
        }
        // We expect start, content and end in each block.
        if (0 !== count($chunks) % 3) {
            throw new InvalidDataException(sprintf('Invalid data'));
        }
        // Remove trailing newlines.
        $chunks = array_map(static fn ($chunk) => ltrim($chunk), $chunks);

        return [$value, array_chunk($chunks, 3)];
    }

    private function getMetadata(string $line)
    {
        if (preg_match(
            '/^(?P<name>[^\s]+)(?P<attributes>(\s+(?P<key>[a-z]+)\s*=\s*"(?P<value>[^"]*)")*)\s+(?P<type>start|end)$/i',
            $line,
            $matches,
            PREG_UNMATCHED_AS_NULL
        )) {
            $metadata = [
                'name' => $matches['name'],
                'type' => $matches['type'],
            ];
            // Extract attributes if any
            if ($matches['attributes']
                && preg_match_all(
                    '/(?P<name>[a-z]+)\s*=\s*"(?P<value>[^"]*)"/',
                    $matches['attributes'],
                    $matches,
                    PREG_SET_ORDER
                )) {
                foreach ($matches as $match) {
                    $metadata['@attributes'][$match['name']] = $match['value'];
                }
            }

            return $metadata;
        }

        throw new InvalidDataException(sprintf('Invalid delimiter: %s', $line));
    }
}
