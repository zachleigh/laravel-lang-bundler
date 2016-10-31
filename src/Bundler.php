<?php

namespace LaravelLangBundler;

use Illuminate\Support\Collection;

class Bundler
{
    /**
     * Array containing mapped lang bundles.
     *
     * @var array
     */
    protected $bundleMap = [];

    /**
     * Get bundle values for given path.
     *
     * @param string $path
     *
     * @return Collection
     */
    public function getBundleValues($path)
    {
        if (empty($this->bundleMap)) {
            $this->mapBundles();
        }

        $pathKeys = $this->getKeysFromPath($path);

        $namespace = array_shift($pathKeys);

        if ($namespace !== 'bundles') {
            return collect([]);
        }

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
        $pathCollection = $this->getPathCollection();

        foreach ($pathCollection as $path) {
            $content = include $path;

            $pathKeys = $this->getPathKeys($path);

            $this->mapContent($content, $pathKeys);
        }

        return $this->getBundleMap();
    }

    /**
     * Get keys from given path.
     *
     * @param string $path
     *
     * @return array
     */
    protected function getKeysFromPath($path)
    {
        $aliases = config('lang-bundler.aliases');

        if (in_array($path, array_keys($aliases))) {
            $path = $aliases[$path];
        }

        return explode('.', $path);
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
