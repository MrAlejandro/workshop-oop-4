<?php

namespace App\DataLoader;

interface HttpLoader
{
    public function getResponseBody(string $url): string;
}
