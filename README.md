# Phalcon Framework - Bridge PSR-11

[![Bridge PSR-11 CI][ci-badge]][ci-link]
[![Quality Gate Status][sonar-quality-badge]][sonar-link]
[![Coverage][sonar-coverage-badge]][sonar-link]
[![PDS Skeleton][pds-skeleton-badge]][pds-skeleton-link]

Phalcon is an open source web framework delivered as a C extension for the PHP language providing high performance and lower resource consumption.

Bridge PSR-11 connects the Phalcon container ([Phalcon\Container\Container][container]) and the PSR-11 (`Psr\Container\ContainerInterface`) standard in both directions:

* **`Container`** - a PSR-11 container backed by a Phalcon container. Use it wherever a `Psr\Container\ContainerInterface` is expected.
* **`Adapter`** - wraps any PSR-11 container so it can be consumed through the Phalcon container's `get()` / `has()` surface.

## Installation

You can install the package using composer

```sh
composer require phalcon/bridge-psr11
```

## Usage

### `Container` - use a Phalcon container through a PSR-11 interface

`Phalcon\Bridge\Psr11\Container` *is* a `Psr\Container\ContainerInterface`, backed by a
Phalcon container. Hand it to any code that expects a PSR-11 container.

```php
use Phalcon\Bridge\Psr11\Container;
use Phalcon\Container\Container as PhalconContainer;

// A configured Phalcon container
$phalcon = new PhalconContainer();
// ... register services on $phalcon ...

$container = new Container($phalcon);

// $container is a Psr\Container\ContainerInterface
if ($container->has('router')) {
    $router = $container->get('router');
}
```

A missing entry throws `Phalcon\Bridge\Psr11\Exception\NotFound` (a
`Psr\Container\NotFoundExceptionInterface`); `has()` never throws.

### `Adapter` - use a PSR-11 container as a Phalcon container

`Phalcon\Bridge\Psr11\Adapter` wraps a `Psr\Container\ContainerInterface` and exposes it
through the Phalcon container's `get()` / `has()` surface. Use it when a PSR-11 container
already exists, such as PHP-DI, and it needs to act as a Phalcon container.

```php
use Phalcon\Bridge\Psr11\Adapter;

// Any Psr\Container\ContainerInterface, e.g. PHP-DI
$psr = new DI\Container(/* ... */);

$container = new Adapter($psr);

if ($container->has('service')) {
    $service = $container->get('service');
}
```

## Development

The repository ships a Docker setup for local development and testing. You only need Docker +
Docker Compose; the PHP runtime and Phalcon are provided inside the container.

### Quick start

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app composer test
```

> `app` is the Compose *service* name. The running container is `bridge-psr11-app` (override with
> `PROJECT_PREFIX`). It stays up via a `sleep infinity` keepalive, so you can
> `docker compose exec app <cmd>` freely (e.g. `composer update`).

### Choosing the PHP version

The image is built for one PHP version at a time, selected with the `PHP_VERSION` build arg
(default `8.5`; supported `8.1`–`8.5`). Because it is a **build** arg, changing it requires a
rebuild (`--build`):

```bash
docker compose up -d --build                  # PHP 8.5 (default)
PHP_VERSION=8.1 docker compose up -d --build  # PHP 8.1
PHP_VERSION=8.4 docker compose up -d --build  # PHP 8.4
```

The container keeps the same name, so each rebuild **replaces** the previous one. To run several
versions side by side, give each its own Compose project and prefix:

```bash
PHP_VERSION=8.1 PROJECT_PREFIX=bridge-psr11-81 docker compose -p bridge-psr11-81 up -d --build
# then: docker exec -w /srv bridge-psr11-81-app composer test
```

### Choosing the backend

The bridge works against either Phalcon runtime, selected with the `PHALCON_VARIANT` build arg:

```bash
docker compose up -d --build                     # package: phalcon/phalcon (v6, default)
PHALCON_VARIANT=ext docker compose up -d --build  # ext: cphalcon C extension (v5)
```

Tip: drop `PHP_VERSION` / `PHALCON_VARIANT` into a `.env` file in the repo root to avoid prefixing
every command — Compose reads it automatically.

### Composer scripts

Run them inside the container, e.g. `docker compose exec app composer cs`:

| Script                                 | Description                                                        |
|----------------------------------------|--------------------------------------------------------------------|
| `composer cs`                          | PHP_CodeSniffer (PSR-12)                                           |
| `composer cs-fix`                      | Auto-fix coding-standard issues (phpcbf)                          |
| `composer cs-fixer`                    | PHP CS Fixer (dry-run)                                             |
| `composer cs-fixer-fix`                | Apply PHP CS Fixer                                                 |
| `composer analyze`                     | PHPStan static analysis                                            |
| `composer test` / `composer test-unit` | Unit tests via [`phalcon/talon`](https://github.com/phalcon/talon) |
| `composer test-coverage`               | Tests + Clover coverage (`tests/_output/coverage.xml`)             |

## Links

### General
* [Official Documentation](https://docs.phalcon.io/)

### Support
* [Forum](https://phalcon.io/forum)
* [Discord](https://phalcon.io/discord)
* [Stack Overflow](https://phalcon.io/so)

### Social Media
* [Telegram](https://phalcon.io/telegram)
* [Gab](https://phalcon.io/gab)
* [LinkedIn](https://phalcon.io/linkedin)
* [MeWe](https://phalcon.io/mewe)
* [Facebook](https://phalcon.io/fb)
* [Twitter](https://phalcon.io/t)


<!-- External links should be here -->
[container]:            https://docs.phalcon.io/latest/container/
[ci-badge]:             https://github.com/phalcon/bridge-psr11/actions/workflows/main.yml/badge.svg?branch=master
[ci-link]:              https://github.com/phalcon/bridge-psr11/actions/workflows/main.yml
[sonar-quality-badge]:  https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr11&metric=alert_status
[sonar-coverage-badge]: https://sonarcloud.io/api/project_badges/measure?project=phalcon_bridge-psr11&metric=coverage
[sonar-link]:           https://sonarcloud.io/summary/new_code?id=phalcon_bridge-psr11
[pds-skeleton-badge]:   https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square
[pds-skeleton-link]:    https://github.com/php-pds/skeleton
[discord-badge]:        https://img.shields.io/discord/310910488152375297?label=Discord&logo=discord&style=flat-square
