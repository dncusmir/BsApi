# BrawStars Api PHP

![CI-CD](https://github.com/dncusmir/BsApi/workflows/CI-CD/badge.svg)
![](https://shepherd.dev/github/dncusmir/BsApi/coverage.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Total Downloads](https://poser.pugx.org/dncusmir/bsapi/downloads)](//packagist.org/packages/dncusmir/bsapi)
[![Stable release](https://poser.pugx.org/dncusmir/bsapi/version.svg)](https://packagist.org/packages/dncusmir/bsapi)

A PHP client to use [BrawStars Api](https://developer.brawlstars.com/#/) implementing [PSR-7](https://www.php-fig.org/psr/psr-7/), [PSR-17](https://www.php-fig.org/psr/psr-17/) and [PSR-18](https://www.php-fig.org/psr/psr-18/).

## Instalation

### Prerequisites

BrawStars Api requires PHP 7.3 or greater.

### Via composer:

```
composer require dncusmir/bsapi
```

It also needs actual implementation for [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-18](https://www.php-fig.org/psr/psr-18/). For example [Guzzle 6 HTTP Adapter](https://github.com/php-http/guzzle6-adapter) for the http client and [laminas-diactoros](https://github.com/laminas/laminas-diactoros) for the http factory.

```
composer require php-http/guzzle6-adapter
composer require laminas/laminas-diactoros
```

## Usage

First make sure to get your api key at (https://developer.brawlstars.com).
Using the client and factory from before:

```php
use Dncusmir\BsApi\BsApi;
use Laminas\Diactoros\RequestFactory;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

// get your client and factory: can use any compatible psr-7 / psr-17 packages
$config = [
    'timeout' => 25,
];
$httpClient = GuzzleAdapter::createWithConfig($config);
$httpRequestFactory = new RequestFactory();

// create your api object to use for api calls
$apiKey = 'your api key';
$api = new BsApi($apiKey, $httpClient, $httpRequestFactory);
```

Now you can make api calls to the [BrawStars Api](https://api.brawlstars.com/v1/).

## Usage examples

##### Getting a player's name:

```php
$playerTag = "#8GVLPUGRJ";

$player = $api->getPlayerInformation($playerTag);

echo $player['name'] . PHP_EOL;
```

##### Getting a player's brawlers and their trophies

```php
foreach ($player['brawlers'] as $brawler) {
    echo $brawler['name'] . ' ' . $brawler['trophies'] . PHP_EOL;
}
```

## Functions

###### Get player information for a specific tag

```php
public function getPlayerInformation(string $playerTag): array
```

###### Get log of recent battles for a player

```php
public function getPlayerBattleLog(string $playerTag): array
```

###### Get club information.

```php
public function getClubInformation(string $clubTag): array
```

###### List club members.

```php
public function getClubMembers(string $clubTag): array
```

###### Get player rankings for a country or global rankings.

```php
public function getPlayerRankings(string $countryCode): array
```

###### Get club rankings for a country or global rankings.

```php
public function getClubRankings(string $countryCode): array
```

###### Get brawler rankings for a country or global rankings.

```php
public function getBrawlerRankings(string $countryCode, string $brawlerId): array
```

###### Get list of available brawlers.

```php
public function getBrawlers(): array
```

###### Get information about a brawler.

```php
public function getBrawlerInformation(string $brawlerId): array
```

## Return format

The return format for each function matches the ones on [BrawlStars Api documentation](https://developer.brawlstars.com/#/documentation).

## Exceptions

All functions throw exceptions: `ClientException` (wrong client configuration, timeout reached etc) and `ResponseException` (bad api key, wrong player tag, etc).



