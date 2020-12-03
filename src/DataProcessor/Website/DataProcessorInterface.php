<?php

namespace App\DataProcessor\Website;

interface DataProcessorInterface
{
    public function getType(array $data): ?string;

    public function getVersion(array $data): ?string;

    public function getData(array $data): ?array;
}
