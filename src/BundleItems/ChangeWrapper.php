<?php

namespace LaravelLangBundler\BundleItems;

class ChangeWrapper extends ItemWrapper
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
        return $this->wrapperParameters['new'];
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
        return $value;
    }
}
