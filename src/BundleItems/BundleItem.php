<?php

namespace LaravelLangBundler\BundleItems;

class BundleItem
{
    /**
     * Lang id of translation.
     *
     * @var string
     */
    protected $id;

    /**
     * Namespace of id. The last value in the id.
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
    protected $parameters = [];

    /**
     * The translation value from lang. Returned in return array.
     *
     * @var mixed
     */
    protected $value = '';

    /**
     * If not null, trans_choice will be called and value passed as countable.
     *
     * @var null|int|Countable
     */
    protected $choice = null;

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
     * Return the value of choice. If not null, trans_choice will be called.
     *
     * @return int|Countable|null
     */
    public function hasChoice()
    {
        return $this->choice;
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
     * Return the final key/value pair array.
     *
     * @return array
     */
    public function getReturnArray()
    {
        return [$this->getKey() => $this->getValue()];
    }

    /**
     * Return the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set choice value on object.
     *
     * @param int|Countable $value
     */
    public function setChoice($value)
    {
        $this->choice = $value;
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
            if (!$key = $this->getNamespacedKey($key)) {
                return [];
            }

            if ($key === 'choice') {
                $this->setChoice($value);
            }

            return [$key => $value];
        });
    }

    /**
     * Get only global and keys with this item's namespace.
     *
     * @param string $key
     *
     * @return string|null
     */
    protected function getNamespacedKey($key)
    {
        $keyArray = explode('.', $key);

        if (count($keyArray) !== 2) {
            return $key;
        } elseif ($keyArray[0] === $this->getNamespace()) {
            return $keyArray[1];
        }
    }

    /**
     * Set the lang value on the object.
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
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
