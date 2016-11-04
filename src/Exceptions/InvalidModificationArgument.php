<?php

namespace LaravelLangBundler\Exceptions;

use Exception;

class InvalidModificationArgument extends Exception
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
            "Target {$target} is not allowed. ".
            "Allowed targets are 'key', 'value', and 'both'."
        );
    }

    /**
     * Invalid classname passed.
     *
     * @param string $className
     *
     * @return static
     */
    public static function modifcationClassNotFound($className)
    {
        return new static("Class {$className} can not be found.");
    }
}
