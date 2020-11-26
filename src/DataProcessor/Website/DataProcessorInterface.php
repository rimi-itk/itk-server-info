<?php

namespace App\DataProcessor\Website;

interface DataProcessorInterface
{
    public function getData(array $serverData): ?array;
}
