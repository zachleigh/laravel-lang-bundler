<?php

namespace LaravelLangBundler\BundleItems;

use Illuminate\Container\Container;
use LaravelLangBundler\BundleItems\BundleItem;

class ItemFactory
{
    /**
     * Build a BundleItem instance.
     *
     * @param string $id
     * @param string $type       Filename_affected
     * @param array  $parameters
     *
     * @return BundleItem
     */
    public static function build($id, $type = null, $parameters = [])
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
