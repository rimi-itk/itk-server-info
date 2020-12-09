<?php

namespace App\DataProcessor\Website;

interface DataProcessorInterface
{
    public function getType(array $websiteData): ?string;

    public function getVersion(array $websiteData): ?string;

    public function getData(array $websiteData): ?array;
}
