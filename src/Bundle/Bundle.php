<?php

namespace LaravelLangBundler\Bundle;

use Illuminate\Support\Collection;
use LaravelLangBundler\BundleItems\BundleItem;
use LaravelLangBundler\BundleItems\ItemFactory;

class Bundle
{
    /**
     * Bundle id provided by client.
     *
     * @var string
     */
    protected $id;

    /**
     * BundleMap instance.
     *
     * @var BundleMap
     */
    protected $bundleMap;

    /**
     * Array of path keys.
     *
     * @var array
     */
    protected $pathKeys = [];

    /**
     * Path resolved from id.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Path namespace.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Collection of lang values.
     *
     * @var Collection
     */
    protected $values;

    /**
     * Construct.
     *
     * @param string $id
     */
    public function __construct($id, BundleMap $bundleMap)
    {
        $this->id = $id;
        $this->path = $id;

        $this->bundleMap = $bundleMap;

        $this->getValuesFromMap();
    }

    /**
     * Get bundle namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get pathKeys array.
     *
     * @return array
     */
    public function getPathKeys()
    {
        return $this->pathKeys;
    }

    /**
     * Get path values collection.
     *
     * @return Collection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Return array of values.
     *
     * @return array
     */
    public function getValuesArray()
    {
        return $this->values->all();
    }

    /**
     * Return true if bundle contains no trans values.
     *
     * @return bool
     */
    public function hasNoValues()
    {
        return $this->values->isEmpty();
    }

    /**
     * Return true if namespace is valid bundle namespace.
     *
     * @return bool
     */
    public function hasValidNamespace()
    {
        return $this->getNamespace() === 'bundles';
    }

    /**
     * Set the namespace on the object.
     *
     * @param string $namespace
     */
    protected function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Set pathKeys on object.
     *
     * @param array $pathKeys
     */
    protected function setPathKeys(array $pathKeys)
    {
        $this->pathKeys = $pathKeys;
    }

    /**
     * Set values collection on object.
     *
     * @param Collection $values
     *
     * @return Collection
     */
    protected function setValues(Collection $values)
    {
        return $this->values = $values->map(function ($value) {
            if (!$value instanceof BundleItem) {
                return ItemFactory::build($value);
            }

            return $value;
        });
    }

    /**
     * Get bundle values from bundle map.
     *
     * @return Collection
     */
    protected function getValuesFromMap()
    {
        if ($this->bundleMap->bundleMapIsEmpty()) {
            $this->bundleMap->mapBundles();
        }

        $this->buildKeys();

        if (!$this->hasValidNamespace()) {
            $this->setValues(collect([]));
        } else {
            $values = $this->bundleMap->getBundleValues($this->getPathKeys());

            $this->setValues($values);
        }
    }

    /**
     * Build pathKeys array and set namespace.
     */
    protected function buildKeys()
    {
        $pathKeys = $this->getKeysFromId();

        $this->setNamespace(array_shift($pathKeys));

        $this->setPathKeys($pathKeys);
    }

    /**
     * Get keys from id.
     *
     * @return array
     */
    protected function getKeysFromId()
    {
        $aliases = config('lang-bundler.aliases');

        $autoAliases = collect($this->bundleMap->getAutoAliases())
            ->filter(function ($value) {
                return $value === $this->id;
            });

        if (in_array($this->id, array_keys($aliases))) {
            $this->path = $aliases[$this->id];
        } elseif ($autoAliases->count() === 1) {
            $this->path = 'bundles.'.$autoAliases->keys()[0];
        }

        return explode('.', $this->path);
    }
}
