<?php

declare(strict_types=1);

namespace Authorizo\Authorizo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Authorizo\Authorizo\Authorizo
 */
class Authorizo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Authorizo\Authorizo\Authorizo::class;
    }
}
