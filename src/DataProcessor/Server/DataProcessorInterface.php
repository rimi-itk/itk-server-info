<?php

namespace App\DataProcessor\Server;

interface DataProcessorInterface
{
    public function getData(array $serverData): ?array;
}
