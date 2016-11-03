<?php

use Illuminate\Container\Container;
use LaravelLangBundler\BundleItems\BundleItem;

if (!function_exists('transB')) {
    /**
     * Translate the given message.
     *
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    function transB($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (is_null($id)) {
            return app('LaravelLangBundler\LangBundler');
        }

        return app('LaravelLangBundler\LangBundler')->trans($id, $parameters, $domain, $locale);
    }
}

if (!function_exists('bundle_item')) {
    /**
     * Create a BundleItem class directly from the bundle registration file.
     *
     * @param string $id
     * @param string $type       Filename_affected
     * @param array  $parameters
     *
     * @return BundleItem
     */
    function bundle_item($id, $type = null, $parameters = [])
    {
        if (is_null($type)) {
            return new BundleItem($id, null, $parameters);
        }

        list($affect, $name) = explode('_', $type);

        $className = ucfirst($name).'Mod';

        $appNamespace = Container::getInstance()->getNamespace();

        $localClass = "\\{$appNamespace}LangBundler\\Mods\\{$className}";

        if (class_exists($localClass)) {
            return new $localClass($id, $affect, $parameters);
        }

        $vendorClass = "\LaravelLangBundler\\BundleItems\\Mods\\{$className}";

        return new $vendorClass($id, $affect, $parameters);
    }
}
