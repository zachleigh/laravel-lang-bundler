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
            $key = $this->getKey($id);

            $id = $this->prefixId($id);

            $value = app('translator')->trans($id, $parameters, $domain, $locale);

            return [$key => $value];
        });
    }

    /**
     * Get key for translation value.
     *
     * @param string $id
     *
     * @return string
     */
    protected function getKey($id)
    {
        $transformMethod = config('lang-bundler.key_transform');

        $key = collect(explode('.', $id))->last();

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
}
