<?php

namespace App\Concerns;


trait Instance
{
    /**
     * @return static
     */
    public static function instance(...$args)
    {
        return new static(...$args);
    }
}
