<?php

namespace LaravelLangBundler;

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
     * @param string $domain
     * @param string $locale
     *
     * @return Collection
     */
    public function trans($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        $bundle = new Bundle($id);

        $this->bundleMap->setBundleValues($bundle);

        if ($bundle->hasNoValues()) {
            return app('translator')->trans($id, $parameters, $domain, $locale);
        }

        return $this->translator->translateBundle($bundle, $parameters, $domain, $locale);
    }
}
