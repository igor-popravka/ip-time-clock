
# IP Time Clock

[![Latest Version](https://img.shields.io/github/release/igor-popravka/ip-time-clock.svg)](https://github.com/igor-popravka/ip-time-clock/releases)
[![PHP Version](https://img.shields.io/packagist/php-v/igor-popravka/ip-time-clock.svg)](https://www.php.net/)
[![License](https://img.shields.io/github/license/igor-popravka/ip-time-clock.svg)](LICENSE)

A Composer package implementing `\Psr\Clock\ClockInterface` that returns the accurate current server time based on its geolocation (determined by IP address).

---

## Purpose

This package provides the server's local time considering its timezone, which is automatically resolved via an external time API based on the server’s IP address. Useful when server time is set to UTC but localized time is needed.

---

## Installation

```bash
composer require igor-popravka/ip-time-clock
```

## Usage

```php
use IpTimeClock\Clock;
use IpTimeClock\Adapter\WorldTimeApiAdapter;
use GuzzleHttp\Client;

// Initialize HTTP client (Guzzle)
$client = new Client();

// Optionally specify an IP address, or null to use server's IP
$ip = null;

// Create adapter
$adapter = new WorldTimeApiAdapter($client, $ip);

// Create Clock instance
$clock = new Clock($adapter);

// Get current localized time
$currentTime = $clock->now();

echo $currentTime->format('Y-m-d H:i:sP');

```

### Configuration

- By default, the package uses **WorldTimeAPI** to determine the timezone from the server's IP address.

- You can specify a different IP address in the adapter constructor to get the time for a different location.

- To use another time API provider, implement your own adapter by implementing the `TimeApiAdapterInterface`.

### Testing

The package includes unit tests that mock the time API adapter to verify the correct behavior of the `Clock` class.

To run tests:

```bash
vendor/bin/phpunit
```