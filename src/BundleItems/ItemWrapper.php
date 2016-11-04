<?php

namespace LaravelLangBundler\BundleItems;

abstract class ItemWrapper extends BundleItem
{
    /**
     * Target: 'key', 'value' or 'both'.
     *
     * @var string
     */
    protected $target;

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
     * @param string $target
     * @param array  $wrapperParameters
     */
    public function __construct($id, $target = null, array $wrapperParameters = [])
    {
        parent::__construct($id);

        $this->target = $target;

        $this->wrapperParameters = $wrapperParameters;
    }

    /**
     * Get the set target.
     *
     * @return string
     */
    public function getAffect()
    {
        return $this->target;
    }

    /**
     * If target is key, get key from child. Otherwise, get from parent.
     *
     * @return string
     */
    public function getKey()
    {
        if ($this->target === 'key' || $this->target === 'both') {
            return $this->key($this->key);
        }

        return parent::getKey();
    }

    /**
     * If target is value, get value from child. Otherwise, get from parent.
     *
     * @return mixed
     */
    public function getValue()
    {
        if ($this->target === 'value' || $this->target === 'both') {
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
