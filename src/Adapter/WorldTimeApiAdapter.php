<?php

namespace IpTimeClock\Adapter;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use GuzzleHttp\ClientInterface;
use IpTimeClock\Contract\TimeApiAdapterInterface;
use RuntimeException;

/**
 * Class WorldTimeApiAdapter
 *
 * Adapter for the WorldTimeAPI (https://worldtimeapi.org/). This class retrieves the current
 * date and time based on the external IP address (or a specified IP), using the Guzzle HTTP client.
 *
 * Implements the TimeApiAdapterInterface to provide a unified way of retrieving time across
 * different providers.
 *
 * @package IpTimeClock\Adapter
 */
class WorldTimeApiAdapter implements TimeApiAdapterInterface
{
    /**
     * WorldTimeApiAdapter constructor.
     *
     * @param ClientInterface $client HTTP client for making API requests.
     * @param string|null $ip Optional IP address to query. If null, the API will use the server's external IP.
     */
    public function __construct(private readonly ClientInterface $client, private readonly ?string $ip = null)
    {
    }

    /**
     * Fetches the current date and time from the WorldTimeAPI.
     *
     * @return DateTimeImmutable The current time with timezone from the API response.
     *
     * @throws RuntimeException If the API call fails or returns an invalid response.
     * @throws Exception If the DateTimeImmutable or DateTimeZone instantiation fails.
     */
    public function getCurrentDateTime(): DateTimeImmutable
    {
        $url = $this->ip
            ? "https://worldtimeapi.org/api/ip/{$this->ip}"
            : "https://worldtimeapi.org/api/ip";

        try {
            $response = $this->client->request('GET', $url, ['timeout' => 5]);
        } catch (\Throwable $e) {
            throw new RuntimeException("HTTP request failed: {$e->getMessage()}");
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['datetime'], $data['timezone'])) {
            throw new RuntimeException("Invalid API response");
        }

        return new DateTimeImmutable($data['datetime'], new DateTimeZone($data['timezone']));
    }
}
