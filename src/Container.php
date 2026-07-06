<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Bridge\Psr11;

use Phalcon\Bridge\Psr11\Exception\ContainerException;
use Phalcon\Bridge\Psr11\Exception\NotFound;
use Phalcon\Contracts\Container\Service\Collection;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Phalcon Bridge PSR-11 Container.
 *
 * A PSR-11 container backed by a Phalcon container. It uses composition so the
 * PSR-11 signatures are declared here and stay valid across both Phalcon
 * runtimes; it delegates `get()`/`has()` to the wrapped container and maps
 * failures to the PSR-typed exceptions. Per PSR-11, `has()` never throws. Use
 * it wherever a Psr\Container\ContainerInterface is expected.
 */
final class Container implements ContainerInterface
{
    public function __construct(private Collection $container)
    {
    }

    public function get(string $id): mixed
    {
        if (false === $this->has($id)) {
            throw new NotFound("No entry found for identifier '" . $id . "'");
        }

        try {
            return $this->container->get($id);
        } catch (Throwable $exception) {
            throw new ContainerException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }

    public function has(string $id): bool
    {
        try {
            return $this->container->has($id);
        } catch (Throwable) {
            return false;
        }
    }
}
