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

use Phalcon\Contracts\Container\Ioc\IocContainer;
use Psr\Container\ContainerInterface;

/**
 * Phalcon Bridge PSR-11 Adapter.
 *
 * Wraps a PSR-11 container so it can be consumed through the Phalcon container
 * IoC surface (`getService()` / `hasService()`). Use it to make any PSR-11
 * container act as a read-only Phalcon service locator.
 */
final class Adapter implements IocContainer
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function getService(string $serviceName): object
    {
        return $this->container->get($serviceName);
    }

    public function hasService(string $serviceName): bool
    {
        return $this->container->has($serviceName);
    }
}
