<?php

namespace LaravelLangBundler\Exceptions;

use Exception;

class InvalidModificationTarget extends Exception
{
    /**
     * Invalid target passed.
     *
     * @param string $target
     *
     * @return static
     */
    public static function targetNotAllowed($target)
    {
        return new static(
            "Target {$target} is not allowed.".
            "Alowed targets are 'key', 'value', and 'both'."
        );
    }
}
