<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;
use LaravelLangBundler\Bundle\Bundle;

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
        array $parameters = [],
        $locale = null
    ) {
        return $bundle->getValues()
            ->mapWithKeys(function ($bundleItem) use ($parameters, $locale) {
                $bundleItem->setParameters($parameters);

                if ($choice = $bundleItem->hasChoice()) {
                    $value = app('translator')->transChoice(
                        $bundleItem->getId(),
                        $choice,
                        $bundleItem->getParameters(),
                        $locale
                    );
                } else {
                    $value = app('translator')->trans(
                        $bundleItem->getId(),
                        $bundleItem->getParameters(),
                        $locale
                    );
                }

                return $bundleItem->setValue($value)->getReturnArray();
            });
    }
}
