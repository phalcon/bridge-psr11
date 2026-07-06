<?php

declare(strict_types=1);

namespace Phalcon\Bridge\Psr11\Tests\Support;

use Psr\Container\ContainerInterface;
use RuntimeException;

use function array_key_exists;

/**
 * A minimal in-memory PSR-11 container used to exercise the import Adapter.
 */
final class InMemoryPsrContainer implements ContainerInterface
{
    /**
     * @param array<string, mixed> $entries
     */
    public function __construct(private array $entries = [])
    {
    }

    public function get(string $id): mixed
    {
        return $this->entries[$id] ?? throw new RuntimeException("No entry for '" . $id . "'");
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->entries);
    }
}
