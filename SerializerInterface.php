<?php

namespace App\Services\Serializer;

interface SerializerInterface
{
    public function support(string $name, $value, array $context = []): bool;

    public function serialize($value, array $context = [], array $settings = []);
}