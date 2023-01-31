<?php

namespace App\Services\Serializer\Concrete;

use App\Services\Serializer\SerializerInterface;

class DateTimeSerializer implements SerializerInterface
{
    public const DATE_FORMAT = 'date_format';

    /**
     * @param \DateTimeInterface $value
     * @param array $context
     * @param array $settings
     * @return string
     */
    public function serialize($value, array $context = [], array $settings = []): string
    {
        return $value->format($settings[self::DATE_FORMAT] ?? 'Y-m-d');
    }

    /**
     * @param string $name
     * @param $value
     * @param array $context
     * @return bool
     */
    public function support(string $name, $value, array $context = []): bool
    {
        return $value instanceof \DateTimeInterface;
    }
}