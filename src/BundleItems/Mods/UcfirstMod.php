<?php

namespace LaravelLangBundler\BundleItems\Mods;

use LaravelLangBundler\BundleItems\ItemWrapper;

class UcfirstMod extends ItemWrapper
{
    /**
     * Alter key and return.
     *
     * @param string $key
     *
     * @return string
     */
    public function key($key)
    {
        return ucfirst($key);
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
        return ucfirst($value);
    }
}
