<?php

namespace LaravelLangBundler\BundleItems;

class ValuesWrapper extends ItemWrapper
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
        return $key;
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
        if (is_array($value)) {
            return array_values($value);
        }

        return $value;
    }
}
