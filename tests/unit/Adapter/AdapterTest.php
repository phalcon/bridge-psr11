<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr11\Tests\Unit\Adapter;

use Phalcon\Bridge\Psr11\Adapter;
use Phalcon\Bridge\Psr11\Tests\Support\Fixtures\Service;
use Phalcon\Bridge\Psr11\Tests\Support\InMemoryPsrContainer;
use Phalcon\Contracts\Container\Ioc\IocContainer;
use PHPUnit\Framework\TestCase;

final class AdapterTest extends TestCase
{
    public function testGetServiceReturnsWrappedEntry(): void
    {
        $service = new Service();
        $adapter = new Adapter(new InMemoryPsrContainer(['service' => $service]));

        $this->assertSame($service, $adapter->getService('service'));
    }

    public function testHasService(): void
    {
        $adapter = new Adapter(new InMemoryPsrContainer(['service' => new Service()]));

        $this->assertTrue($adapter->hasService('service'));
        $this->assertFalse($adapter->hasService('missing'));
    }

    public function testIsIocContainer(): void
    {
        $this->assertInstanceOf(IocContainer::class, new Adapter(new InMemoryPsrContainer()));
    }
}
