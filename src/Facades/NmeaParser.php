<?php

namespace Enesisrl\LaravelNmeaParser\Facades;

use Illuminate\Support\Facades\Facade;

class NmeaParser extends Facade {

    protected static function getFacadeAccessor(): string
    {
        return 'NmeaParser';
    }

}
