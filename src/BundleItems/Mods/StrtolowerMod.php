<?php

namespace LaravelLangBundler\BundleItems\Mods;

use LaravelLangBundler\BundleItems\ItemWrapper;

class StrtolowerMod extends ItemWrapper
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
        return strtolower($key);
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
        return strtolower($value);
    }
}
