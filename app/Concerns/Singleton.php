<?php

namespace App\Concerns;


trait Singleton
{
    /**
     * @var static
     */
    protected static $singleton;
    /**
     * @return static
     */
    public static function singleton(...$args)
    {
        if(!isset(static::$singleton)) {
            static::$singleton = new static(...$args);
        }
        return static::$singleton;
    }
}
