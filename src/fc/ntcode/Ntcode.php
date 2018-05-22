<?php
namespace fc\ntcode;
use Illuminate\Support\Facades\Facade;
class Ntcode extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'ntcode';
    }
}