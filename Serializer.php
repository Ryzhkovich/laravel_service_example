<?php
declare(strict_types=1);

namespace App\Services\Serializer;

/**
 * Заменить после обновления laravel symfony/serializer
 */
class Serializer
{
    const SKIP_NULL_VALUES = 'skip_null_values';

    /**
     * @var iterable<SerializerInterface>
     */
    private $serializers;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @param iterable<SerializerInterface> $serializers
     */
    public function __construct(SerializerInterface ...$serializers)
    {
        $this->serializers = $serializers;
    }

    /**
     * @param $value
     * @param array $context
     * @param array $settings
     * @return array|bool|float|int|string
     * @throws \ReflectionException
     */
    public function serialize($value, array $context = [], array $settings = [])
    {
        $serialized = [];
        $iterationSettings = array_merge($this->settings, $settings);

        if (is_scalar($value)) {
            return $value;
        }
        if (is_array($value)) {
            foreach ($value as $item) {
                $serialized[] = $this->serialize($item, $context, $settings);
            }
            return $serialized;
        }

        $reflectionClass = new \ReflectionClass($value);

        foreach($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (stripos($method->name, 'get') !== 0) {
                continue;
            }

            $propertyValue = call_user_func([$value, $method->name]);

            $propertyName = lcfirst(substr($method->name, strlen('get')));
            $serializedValue = $this->serializeValue($propertyName, $propertyValue, $context, $settings);
            if (($iterationSettings[self::SKIP_NULL_VALUES] ?? false) && $serializedValue === null) {
                continue;
            }
            $serialized[$propertyName] = $serializedValue;
        }

        return $serialized;
    }

    /**
     * @param $name
     * @param $value
     * @param array $context
     * @param array $settings
     * @return array|bool|float|int|mixed|string
     * @throws \ReflectionException
     */
    private function serializeValue($name, $value, array $context, array $settings)
    {
        if (empty($value)) {
            return $value;
        }
        $concrete = $this;

        foreach ($this->serializers as $serializer) {
            if ($serializer->support($name, $value, $context) === true) {
                $concrete = $serializer;
            }
        }

        return $concrete->serialize($value, $context, $settings);
    }
}