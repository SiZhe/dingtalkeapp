<?php
namespace Ghlin\DingtalkEapp\Facades;

use Illuminate\Support\Facades\Facade;

class DingtalkEapp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dingtalkeapp';
    }
}