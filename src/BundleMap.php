<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;

class BundleMap
{
    /**
     * Array containing mapped lang bundles.
     *
     * @var array
     */
    protected $bundleMap = [];

    /**
     * Array of auto-aliased file names.
     *
     * @var array
     */
    protected $autoAliases = [];

    /**
     * Get bundle values for given path.
     *
     * @param Bundle $bundle
     *
     * @return Collection
     */
    public function setBundleValues(Bundle $bundle)
    {
        if (empty($this->bundleMap)) {
            $this->mapBundles();
        }

        $bundle->buildKeys($this->autoAliases);

        if (!$bundle->hasValidNamespace()) {
            return $bundle->setValues(collect([]));
        }

        $values = $this->getBundleValues($bundle->getPathKeys());

        return $bundle->setValues($values);
    }

    /**
     * Get trans values for path keys.
     *
     * @param array $pathKeys
     *
     * @return Collection
     */
    protected function getBundleValues(array $pathKeys)
    {
        $temp = &$this->bundleMap;

        foreach ($pathKeys as $key) {
            $temp = &$temp[$key];
        }

        return collect($temp);
    }

    /**
     * Return bundle map.
     *
     * @return array
     */
    public function getBundleMap()
    {
        return $this->bundleMap;
    }

    /**
     * Get all the lang bundle files from the bundles directory.
     *
     * @return array
     */
    public function mapBundles()
    {
        if (!empty($this->bundleMap)) {
            return $this->getBundleMap();
        }

        $pathCollection = $this->getPathCollection();

        foreach ($pathCollection as $path) {
            $content = include $path;

            $pathKeys = $this->getPathKeys($path);

            $this->registerAlias(collect($pathKeys));

            $this->mapContent($content, $pathKeys);
        }

        return $this->getBundleMap();
    }

    /**
     * Get paths to all bundle files.
     *
     * @return Collection
     */
    protected function getPathCollection()
    {
        $bundlePath = resource_path('lang/bundles');

        if (!file_exists($bundlePath)) {
            return collect([]);
        }

        $directory = new \RecursiveDirectoryIterator($bundlePath);

        $iterator = new \RecursiveIteratorIterator($directory);

        $paths = new \RegexIterator(
            $iterator,
            '/^.+\.php$/i',
            \RecursiveRegexIterator::GET_MATCH
        );

        return collect(iterator_to_array($paths))->flatten();
    }

    /**
     * Get array of keys describing file path.
     *
     * @param string $path
     *
     * @return array
     */
    protected function getPathKeys($path)
    {
        $key = str_replace('.php', '', explode('/bundles/', $path)[1]);

        return explode('/', $key);
    }

    /**
     * Register file paths as auto aliases.
     *
     * @param Collection $pathKeys
     */
    protected function registerAlias(Collection $pathKeys)
    {
        $className = $pathKeys->last();

        $path = $pathKeys->implode('.');

        $this->autoAliases[$path] = $className;
    }

    /**
     * Map content on bundleMap.
     *
     * @param array $content
     * @param array $pathKeys
     */
    protected function mapContent(array $content, array $pathKeys)
    {
        $temp = &$this->bundleMap;

        foreach ($pathKeys as $key) {
            $temp = &$temp[$key];
        }

        $temp = $content;
    }
}
