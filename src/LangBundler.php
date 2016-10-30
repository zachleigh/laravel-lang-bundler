<?php

namespace LaravelLangBundler;

class LangBundler
{
    /**
     * Bundler instance.
     *
     * @var Bundler
     */
    protected $bundler;

    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Construct.
     */
    public function __construct(Bundler $bundler, Translator $translator)
    {
        $this->bundler = $bundler;
        $this->translator = $translator;
    }

    /**
     * Get translations for a bundle.
     *
     * @param string $id
     *
     * @return Collection
     */
    public function trans($id, $parameters = [], $domain = 'messages', $locale = null)
    {
        $values = $this->bundler->getBundleValues($id);

        if ($values->isEmpty()) {
            return app('translator')->trans($id, $parameters, $domain, $locale);
        }

        return $this->translator->translateBundle($values, $parameters, $domain, $locale);
    }
}
