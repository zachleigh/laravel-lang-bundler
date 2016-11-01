<?php

namespace LaravelLangBundler\BundleItems;

use LaravelLangBundler\BundleItem;

abstract class ItemWrapper extends BundleItem
{
    /**
     * Affect 'key' or 'value'.
     *
     * @var string
     */
    protected $affect;

    /**
     * Any parameters needed for the effect.
     *
     * @var array
     */
    protected $wrapperParameters = [];

    /**
     * Construct.
     *
     * @param string $id
     * @param string $affect
     * @param array  $wrapperParameters
     */
    public function __construct($id, $affect = null, array $wrapperParameters = [])
    {
        parent::__construct($id);

        $this->affect = $affect;

        $this->wrapperParameters = $wrapperParameters;
    }

    /**
     * If affect is key, get key from child. Otherwise, get from parent.
     *
     * @return string
     */
    public function getKey()
    {
        if ($this->affect === 'key') {
            return $this->key($this->key);
        }

        return parent::getKey();
    }

    /**
     * If affect is value, get value from child. Otherwise, get from parent.
     *
     * @return mixed
     */
    public function getValue()
    {
        if ($this->affect === 'value') {
            return $this->value($this->value);
        }

        return parent::getValue();
    }

    /**
     * Alter key and return.
     *
     * @param string $key
     *
     * @return string
     */
    abstract public function key($key);

    /**
     * Alter value and return.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function value($value);
}
