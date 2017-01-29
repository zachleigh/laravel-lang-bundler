<?php

namespace LaravelLangBundler\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LaravelLangBundlerCommand extends Command
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Set up command dependencies.
     */
    protected function setUp()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Build path from path array.
     *
     * @param array  $pathArray
     * @param string $path
     *
     * @return string
     */
    protected function buildPath($pathArray, $path)
    {
        foreach ($pathArray as $directory) {
            $path .= $directory.'/';

            if (!$this->filesystem->exists($path)) {
                $this->filesystem->makeDirectory($path);
            }
        }

        return $path;
    }
}
