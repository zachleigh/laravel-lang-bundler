<?php

namespace LaravelLangBundler;

class Translation
{
    /**
     * Lang id of translation.
     *
     * @var string
     */
    protected $id;

    /**
     * Namespace of id.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Key to be used in return array.
     *
     * @var string
     */
    protected $key;

    /**
     * Collection of valid parameters.
     *
     * @var Collection
     */
    protected $parameters;

    /**
     * Construct.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $namespace = $this->setNamespace($id);

        $this->setKey($namespace);

        $this->setId($id);
    }

    /**
     * Return the translation key for return array.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the Laravel lang id of translation.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the namespace for the translation.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Return array of valid parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Set only valid parameters. If namespaced, only parameters with
     * translation namespace will be set.
     *
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = collect($parameters)->mapWithKeys(function ($value, $key) {
            $keyArray = explode('.', $key);

            if (count($keyArray) === 2 && $keyArray[0] === $this->getNamespace()) {
                $key = $keyArray[1];
            } elseif (count($keyArray) === 2 && $keyArray[0] !== $this->getNamespace()) {
                return;
            }

            return [$key => $value];
        });
    }

    /**
     * Set the translation namespace on the object.
     */
    protected function setNamespace($id)
    {
        return $this->namespace = collect(explode('.', $id))->last();
    }

    /**
     * Get key for translation value.
     *
     * @return string
     */
    protected function setKey($namespace)
    {
        $transformMethod = config('lang-bundler.key_transform');

        if ($transformMethod === 'none') {
            return $this->key = $namespace;
        }

        return $this->key = $transformMethod($namespace);
    }

    /**
     * Prefix the translation id with global_key_namespace, if set.
     *
     * @param string $id
     *
     * @return string
     */
    protected function setId($id)
    {
        $prefix = config('lang-bundler.global_key_namespace');

        if (!empty($prefix)) {
            $id = $prefix.'.'.$id;
        }

        $this->id = $id;
    }
}
