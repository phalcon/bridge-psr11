<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr11\Tests\Unit\Container;

use Phalcon\Bridge\Psr11\Container;
use Phalcon\Bridge\Psr11\Exception\ContainerException;
use Phalcon\Bridge\Psr11\Exception\NotFound;
use Phalcon\Bridge\Psr11\Tests\Support\Fixtures\Service;
use Phalcon\Container\Container as PhalconContainer;
use Phalcon\Contracts\Container\Service\Collection;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class ContainerTest extends TestCase
{
    public function testGetMissingThrowsPsrNotFound(): void
    {
        $container = new Container(new PhalconContainer());

        $this->expectException(NotFound::class);

        $container->get('missing-service-xyz');
    }

    public function testGetResolvesRegisteredService(): void
    {
        $phalcon = new PhalconContainer();
        $phalcon->set('service', Service::class);

        $container = new Container($phalcon);

        $this->assertTrue($container->has('service'));
        $this->assertInstanceOf(Service::class, $container->get('service'));
    }

    public function testGetWrapsResolutionErrorInContainerException(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('has')->willReturn(true);
        $collection->method('get')->willThrowException(new RuntimeException('boom'));

        $container = new Container($collection);

        $this->expectException(ContainerException::class);

        $container->get('service');
    }

    public function testHasNeverThrows(): void
    {
        $collection = $this->createMock(Collection::class);
        $collection->method('has')->willThrowException(new RuntimeException('boom'));

        $container = new Container($collection);

        // has() must swallow any underlying throw and return false (PSR-11).
        $this->assertFalse($container->has('anything'));
    }

    public function testHasReturnsFalseForMissing(): void
    {
        $container = new Container(new PhalconContainer());

        $this->assertFalse($container->has('missing-service-xyz'));
    }

    public function testIsPsrContainer(): void
    {
        $this->assertInstanceOf(ContainerInterface::class, new Container(new PhalconContainer()));
    }

    public function testMissingExceptionIsPsrTyped(): void
    {
        $container = new Container(new PhalconContainer());

        try {
            $container->get('missing-service-xyz');
            $this->fail('Expected a NotFound exception');
        } catch (NotFoundExceptionInterface $exception) {
            $this->assertInstanceOf(NotFound::class, $exception);
        }
    }
}
