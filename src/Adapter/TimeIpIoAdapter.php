<?php

namespace IpTimeClock\Adapter;

use DateTimeImmutable;
use DateTimeZone;
use GuzzleHttp\ClientInterface;
use IpTimeClock\Contract\TimeApiAdapterInterface;
use IpTimeClock\Exception\TimeApiException;

/**
 * Adapter for retrieving the current date and time based on IP address
 * using the https://timeapi.io public API.
 */
class TimeIpIoAdapter implements TimeApiAdapterInterface
{
    /**
     * @param ClientInterface $client Guzzle HTTP client for performing requests.
     * @param string|null     $ip     Optional IP address for geolocation; if null, the server IP will be used.
     */
    public function __construct(
        private readonly ClientInterface $client,
        private readonly ?string $ip = null
    ) {}

    /**
     * Returns the current date and time based on the provided IP address.
     *
     * @return DateTimeImmutable The current date and time.
     *
     * @throws TimeApiException If the HTTP request fails or the response is invalid.
     */
    public function getCurrentDateTime(): DateTimeImmutable
    {
        $ip = $this->ip ?? $this->resolvePublicIp();
        $url = "https://timeapi.io/api/Time/current/ip?ipAddress=$ip";

        try {
            $response = $this->client->request('GET', $url, ['timeout' => 5]);
            $body = (string) $response->getBody();
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new TimeApiException("Invalid JSON response: " . $e->getMessage(), 0, $e);
        } catch (\Throwable $e) {
            throw new TimeApiException("HTTP request failed: " . $e->getMessage(), 0, $e);
        }

        if (empty($data['dateTime']) || empty($data['timeZone'])) {
            throw new TimeApiException("Invalid API response structure: missing 'dateTime' or 'timeZone'");
        }

        try {
            return new DateTimeImmutable($data['dateTime'], new DateTimeZone($data['timeZone']));
        } catch (\Throwable $e) {
            throw new TimeApiException("Failed to create DateTimeImmutable: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Resolves the server's public IP address using ipify.org.
     *
     * @return string
     *
     * @throws TimeApiException
     */
    private function resolvePublicIp(): string
    {
        try {
            $response = $this->client->request('GET', 'https://api.ipify.org', ['timeout' => 3]);
            return trim((string)$response->getBody());
        } catch (\Throwable $e) {
            throw new TimeApiException("Failed to resolve public IP: {$e->getMessage()}", 0, $e);
        }
    }
}
