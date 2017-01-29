<?php

namespace LaravelLangBundler;

use LaravelLangBundler\Bundle\Bundle;
use LaravelLangBundler\Bundle\BundleMap;

class LangBundler
{
    /**
     * BundleMap instance.
     *
     * @var BundleMap
     */
    protected $bundleMap;

    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Construct.
     */
    public function __construct(BundleMap $bundleMap, Translator $translator)
    {
        $this->bundleMap = $bundleMap;
        $this->translator = $translator;

        $this->bundleMap->mapBundles();
    }

    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array  $parameters
     * @param string $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public function trans($id, array $parameters = [], $locale = null)
    {
        $bundle = new Bundle($id, $this->bundleMap);

        if ($bundle->hasNoValues()) {
            return app('translator')->trans($id, $parameters, $locale);
        }

        return $this->translator->translateBundle($bundle, $parameters, $locale);
    }
}
