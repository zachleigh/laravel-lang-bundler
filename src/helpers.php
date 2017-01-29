<?php

use LaravelLangBundler\BundleItems\ItemFactory;

if (!function_exists('transB')) {
    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array  $parameters
     * @param string $locale
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    function transB($id = null, array $parameters = [], $locale = null)
    {
        if (is_null($id)) {
            return app('LaravelLangBundler\LangBundler');
        }

        return app('LaravelLangBundler\LangBundler')->trans($id, $parameters, $locale);
    }
}

if (!function_exists('bundle_item')) {
    /**
     * Create a BundleItem class directly from the bundle registration file.
     *
     * @param string $id
     * @param string $type       target_modType
     * @param array  $parameters
     *
     * @return BundleItem
     */
    function bundle_item($id, $type = null, array $parameters = [])
    {
        return ItemFactory::build($id, $type, $parameters);
    }
}
