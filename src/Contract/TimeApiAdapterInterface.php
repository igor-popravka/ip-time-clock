<?php

namespace IpTimeClock\Contract;

use DateTimeImmutable;

/**
 * Interface TimeApiAdapterInterface
 *
 * Defines the contract for any time API adapter used to retrieve the current
 * date and time in a specific timezone, typically based on IP geolocation.
 *
 * Implementations of this interface should encapsulate the logic for calling
 * external time APIs and converting
 * the result into a DateTimeImmutable object.
 *
 * @package IpTimeClock\Contract
 */
interface TimeApiAdapterInterface
{
    /**
     * Get the current date and time from the external time API.
     *
     * @return DateTimeImmutable The current time in the resolved timezone.
     *
     * @throws \RuntimeException If the time data could not be retrieved or parsed.
     */
    public function getCurrentDateTime(): DateTimeImmutable;
}
