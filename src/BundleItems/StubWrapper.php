<?php

namespace LaravelLangBundler\BundleItems;

class StubWrapper extends ItemWrapper
{
    /*
    |--------------------------------------------------------------------------
    | ItemWrapper Classes
    |--------------------------------------------------------------------------
    |
    | These classes are used to modify the returned key and value for a specific
    | bundle item. The classname must end with 'Wrapper' and both key and value
    | logic should be included. If one is not necessary, simply return the
    | $key/$value. Parameters for wrapper classes are stored on the parent as
    | $wrapperParameters.
    */

    /**
     * Alter key and return.
     *
     * @param string $key
     *
     * @return string
     */
    public function key($key)
    {
    }

    /**
     * Alter value and return.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function value($value)
    {
    }
}
