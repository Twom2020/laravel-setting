<?php


namespace Twom\Setting\src\Facade;


use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        $driver = config("setting.driver");
        if ($driver) {
            $driver = config("setting.drivers.{$driver}.provider");
            if ($driver && is_callable($driver))
                return $driver;
            return \Twom\Setting\src\Drivers\DB::class; // should remove
//            throw new \Exception("invalid setting driver.");
        }
    }
}
