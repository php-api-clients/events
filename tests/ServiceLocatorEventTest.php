<?php declare(strict_types=1);

namespace ApiClients\Tests\Foundation\Events;

use ApiClients\Foundation\Events\ServiceLocatorEvent;
use ApiClients\Tools\TestUtilities\TestCase;

final class ServiceLocatorEventTest extends TestCase
{
    public function testName()
    {
        $this->assertSame(ServiceLocatorEvent::NAME, ServiceLocatorEvent::create()->getName());
    }

    public function testEvent()
    {
        $event = ServiceLocatorEvent::create();

        $this->assertSame([], iterator_to_array($event->getMap()));

        $event->add(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test_app' . DIRECTORY_SEPARATOR . 'ServiceHelicopter',
            'ApiClients\TestApp\Foundation\ServiceHelicopter'
        );
        $this->assertSame([
            'ApiClients\TestApp\Foundation\ServiceHelicopter\HelicopterService',
        ], iterator_to_array($event->getMap()));

    }
}
