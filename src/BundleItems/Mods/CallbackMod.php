<?php

namespace LaravelLangBundler\BundleItems\Mods;

use LaravelLangBundler\BundleItems\ItemWrapper;

class CallbackMod extends ItemWrapper
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
        return $this->callCallback($key);
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
        return $this->callCallback($value);
    }

    /**
     * Call the given callback.
     *
     * @param callable $string
     *
     * @return string
     */
    protected function callCallback($string)
    {
        if (is_callable($this->wrapperParameters['callback'])) {
            $callback = $this->wrapperParameters['callback'];

            return $callback($string);
        }
    }
}
