<?php

namespace App\Domains\Account\Facades;

use Illuminate\Support\Facades\Facade;

class Ledger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ledger';
    }
}
