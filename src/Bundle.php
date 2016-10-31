<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;

class Bundle
{
    /**
     * Bundle id provided by client.
     *
     * @var string
     */
    protected $id;

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
    protected $path;

    /**
     * Path namespace.
     *
     * @var string
     */
    protected $namespace;

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
    public function __construct($id)
    {
        $this->id = $id;
        $this->path = $id;
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
     * Set values collection on object.
     *
     * @param Collection $values
     *
     * @return Collection
     */
    public function setValues(Collection $values)
    {
        return $this->values = $values;
    }

    /**
     * Return true if bundle contains no trans values.
     *
     * @return boolean
     */
    public function hasNoValues()
    {
        return $this->values->isEmpty();
    }

    /**
     * Build pathKeys array and set namespace.
     *
     * @param array $autoAliases
     */
    public function buildKeys(array $autoAliases = [])
    {
        $this->getKeysFromId($autoAliases);

        $this->namespace = array_shift($this->pathKeys);
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
     * Get keys from id.
     *
     * @param array $autoAliases
     *
     * @return array
     */
    protected function getKeysFromId(array $autoAliases = [])
    {
        $aliases = config('lang-bundler.aliases');

        $autoAliases = collect($autoAliases)
            ->filter(function ($value) {
                return $value === $this->id;
            });

        if (in_array($this->id, array_keys($aliases))) {
            $this->path = $aliases[$this->id];
        } elseif ($autoAliases->count() === 1) {
            $this->path = 'bundles.'.$autoAliases->keys()[0];
        }

        $this->pathKeys = explode('.', $this->path);
    }
}
