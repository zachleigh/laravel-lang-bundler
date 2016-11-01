<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;

class Translator
{
    /**
     * Translate the values in given bundle.
     *
     * @param Bundle $bundle
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return Collection
     */
    public function translateBundle(
        Bundle $bundle,
        $parameters = [],
        $domain = 'messages',
        $locale = null
    ) {
        return $bundle->getValues()
            ->mapWithKeys(function ($translation) use ($parameters, $domain, $locale) {
                $translation->setParameters($parameters);

                $value = app('translator')->trans(
                    $translation->getId(),
                    $translation->getParameters(),
                    $domain,
                    $locale
                );

                return [$translation->getKey() => $value];
            });
    }
}
