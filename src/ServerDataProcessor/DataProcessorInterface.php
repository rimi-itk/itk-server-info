<?php

namespace App\ServerDataProcessor;

interface DataProcessorInterface
{
    public function getData(array $serverData): ?array;
}
