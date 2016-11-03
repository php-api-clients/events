<?php declare(strict_types=1);

namespace ApiClients\Foundation\Events;

use ApiClients\Foundation\Service\ServiceInterface;
use Generator;
use League\Event\AbstractEvent;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class ServiceLocatorEvent extends AbstractEvent
{
    const NAME = 'api-clients.foundation.service-locator';

    /**
     * @var array
     */
    private $map = [];

    /**
     * @return ServiceLocatorEvent
     */
    public static function create(): ServiceLocatorEvent
    {
        return new self();
    }

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param string $path
     * @param string $namespace
     */
    public function add(string $path, string $namespace)
    {
        $this->map[] = [
            'path' => $path,
            'namespace' => $namespace,
        ];
    }

    /**
     * @return Generator
     */
    public function getMap(): Generator
    {
        foreach ($this->map as $map) {
            yield from $this->map($map);
        }
    }

    /**
     * @param array $map
     * @return Generator
     */
    private function map($map): Generator
    {
        $directory = new RecursiveDirectoryIterator($map['path']);
        $directory = new RecursiveIteratorIterator($directory);

        foreach ($directory as $node) {
            if (!is_file($node->getPathname())) {
                continue;
            }

            $file = substr($node->getPathname(), strlen($map['path']));
            $file = ltrim($file, DIRECTORY_SEPARATOR);
            $file = rtrim($file, '.php');

            $class = $map['namespace'] . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $file);

            if (!class_exists($class)) {
                continue;
            }

            if (!is_subclass_of($class, ServiceInterface::class)) {
                continue;
            }

            yield $class;
        }
    }
}
