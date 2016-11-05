<?php

namespace ApiClients\TestApp\Foundation\ServiceHelicopter;

use ApiClients\Foundation\Service\ServiceInterface;
use React\Promise\CancellablePromiseInterface;
use function React\Promise\resolve;

class HelicopterService implements ServiceInterface
{
    public function handle($input): CancellablePromiseInterface
    {
        return resolve($input);
    }
}
