<?php

namespace LaravelLangBundler\BundleItems;

use Illuminate\Container\Container;
use LaravelLangBundler\Exceptions\InvalidModificationArgument;

class ItemFactory
{
    /**
     * Allowed values for BundleItem target property.
     *
     * @var array
     */
    const ALLOWEDTARGETS = [
        'value',
        'key',
        'both',
    ];

    /**
     * Build a BundleItem instance.
     *
     * @param string $id
     * @param string $type       Filename_affected
     * @param array  $parameters
     *
     * @return BundleItem
     */
    public static function build($id, $type = null, array $parameters = [])
    {
        if (is_null($type)) {
            return new BundleItem($id, null, $parameters);
        }

        list($target, $name) = explode('_', $type);

        self::validateTarget($target);

        $className = ucfirst($name).'Mod';

        $appNamespace = Container::getInstance()->getNamespace();

        $localClass = "\\{$appNamespace}LangBundler\\Mods\\{$className}";

        $vendorClass = "\LaravelLangBundler\\BundleItems\\Mods\\{$className}";

        if (class_exists($localClass)) {
            return new $localClass($id, $target, $parameters);
        } elseif (class_exists($vendorClass)) {
            return new $vendorClass($id, $target, $parameters);
        }

        throw InvalidModificationArgument::modifcationClassNotFound($className);
    }

    /**
     * Validate the target.
     *
     * @param string $target
     *
     * @throws InvalidModificationTarget
     */
    protected static function validateTarget($target)
    {
        if (!in_array($target, self::ALLOWEDTARGETS)) {
            throw InvalidModificationArgument::targetNotAllowed($target);
        }
    }
}
