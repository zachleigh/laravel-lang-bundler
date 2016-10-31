<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;

class Translator
{
    /**
     * Translate the values in given bundle.
     *
     * @param Collection $bundle
     *
     * @return Collection
     */
    public function translateBundle(
        Collection $bundle,
        $parameters = [],
        $domain = 'messages',
        $locale = null
    ) {
        return $bundle->mapWithKeys(function ($id) use ($parameters, $domain, $locale) {
            $namespace = collect(explode('.', $id))->last();

            $key = $this->getKey($namespace);

            $id = $this->prefixId($id);

            $parameters = $this->getNamespacedParameters($parameters, $namespace);

            $value = app('translator')->trans($id, $parameters, $domain, $locale);

            return [$key => $value];
        });
    }

    /**
     * Get key for translation value.
     *
     * @param string $key
     *
     * @return string
     */
    protected function getKey($key)
    {
        $transformMethod = config('lang-bundler.key_transform');

        if ($transformMethod === 'none') {
            return $key;
        }

        return $transformMethod($key);
    }

    /**
     * Prefix the translation id with global_key_namespace, if set.
     *
     * @param string $id
     *
     * @return string
     */
    protected function prefixId($id)
    {
        $prefix = config('lang-bundler.global_key_namespace');

        if (!empty($prefix)) {
            return $prefix.'.'.$id;
        }

        return $id;
    }

    /**
     * Get valid, namespaced parameters.
     *
     * @param array  $parameters
     * @param string $namespace
     *
     * @return array
     */
    protected function getNamespacedParameters($parameters, $namespace)
    {
        return collect($parameters)->mapWithKeys(function ($value, $key) use ($namespace) {
            $keyArray = explode('.', $key);

            if (count($keyArray) === 2 && $keyArray[0] === $namespace) {
                $key = $keyArray[1];
            }

            return [$key => $value];
        })->all();
    }
}
