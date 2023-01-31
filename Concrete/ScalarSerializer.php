<?php

namespace App\Services\Serializer\Concrete;

use App\Services\Serializer\SerializerInterface;

class ScalarSerializer implements SerializerInterface
{
    /**
     * @param string $name
     * @param $value
     * @param array $context
     * @return bool
     */
    public function support(string $name, $value, array $context = []): bool
    {
        return is_scalar($value);
    }

    /**
     * @param $value
     * @param array $context
     * @param array $settings
     * @return mixed
     */
    public function serialize($value, array $context = [], array $settings = [])
    {
        return $value;
    }
}