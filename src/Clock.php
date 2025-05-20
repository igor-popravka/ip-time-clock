<?php

namespace IpTimeClock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;
use IpTimeClock\Contract\TimeApiAdapterInterface;

/**
 * Class Clock
 *
 * This class implements PSR-20 ClockInterface and provides the current
 * date and time based on a custom time source adapter.
 *
 * It delegates the logic of obtaining the correct time to a TimeApiAdapterInterface implementation,
 * which typically uses an external time API based on the server's IP address.
 *
 * @package IpTimeClock
 */
class Clock implements ClockInterface
{
    /**
     * Create a new Clock instance.
     *
     * @param TimeApiAdapterInterface $adapter The adapter responsible for providing the current time.
     */
    public function __construct(private readonly TimeApiAdapterInterface $adapter)
    {
    }

    /**
     * Returns the current date and time as provided by the configured adapter.
     *
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable
    {
        return $this->adapter->getCurrentDateTime();
    }
}
