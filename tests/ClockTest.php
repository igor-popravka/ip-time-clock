<?php

namespace IpTimeClock\Tests;

use DateTimeZone;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use IpTimeClock\Clock;
use IpTimeClock\Contract\TimeApiAdapterInterface;
use DateTimeImmutable;

/**
 * Class ClockTest
 *
 * Unit test for the Clock class, which is a PSR-20 compatible implementation
 * that delegates time retrieval to a Time API adapter.
 *
 * @package IpTimeClock\Tests
 */
class ClockTest extends TestCase
{
    /**
     * Tests that the now() method returns the expected DateTimeImmutable instance.
     *
     * @return void
     *
     * @throws Exception
     */
    public function testNowReturnsDateTimeImmutable(): void
    {
        $mock = $this->createMock(TimeApiAdapterInterface::class);
        $expected = new DateTimeImmutable('2025-01-01 12:00:00', new DateTimeZone('UTC'));
        $mock->method('getCurrentDateTime')->willReturn($expected);

        $clock = new Clock($mock);

        $this->assertEquals($expected, $clock->now());
    }
}
