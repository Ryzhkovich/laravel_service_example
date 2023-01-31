<?php
declare(strict_types=1);

namespace Services\Serializer\Concrete;

use App\Services\Serializer\Concrete\DateTimeSerializer;
use PHPUnit\Framework\TestCase;

class DateTimeSerializerTest extends TestCase
{
    public function testCanSupportOwnSerialization(): void
    {
        $serializer = new DateTimeSerializer();

        $this->assertTrue($serializer->support('some Name', new \DateTimeImmutable()));
    }

    public function testCanSerializeValue(): void
    {
        $serializer = new DateTimeSerializer();

        $this->assertEquals(
            '2022-10-02',
            $serializer->serialize(new \DateTimeImmutable('2022-10-02'))
        );
    }

    public function testCanSerializeValueWithFormat(): void
    {
        $serializer = new DateTimeSerializer();

        $this->assertEquals(
            '02.10.2022',
            $serializer->serialize(
                new \DateTimeImmutable('2022-10-02'),
                [],
                [DateTimeSerializer::DATE_FORMAT => 'd.m.Y']
            )
        );
    }
}