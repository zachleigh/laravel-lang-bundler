<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class BundleMapTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_doesnt_map_bundles_when_bundles_not_present()
    {
        $map = $this->bundleMap->mapBundles();

        $this->assertEquals([], $map);
    }

    /**
     * @test
     */
    public function it_maps_a_single_dimension_array()
    {
        $this->copyStubs('bundle1');

        $map = $this->bundleMap->mapBundles();

        $expected = $this->getExpected('bundle1');

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_maps_a_multidimensional_array()
    {
        $this->copyStubs('bundle2');

        $map = $this->bundleMap->mapBundles();

        $expected = $this->getExpected('bundle2');

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_maps_multiple_files()
    {
        $bundles = ['bundle1', 'bundle2'];

        $this->copyStubs($bundles);

        $map = $this->bundleMap->mapBundles();

        $expected = $this->getExpected($bundles);

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_maps_layered_directories()
    {
        $this->copyStubs('components');

        $map = $this->bundleMap->mapBundles();

        $expected = [
            'components' => [
                'sub-components' => $this->getExpected('bundle4'),
                'bundle3'        => $this->getExpected('bundle3')['bundle3'],
            ],
        ];

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_gets_values_for_a_single_dimension_array()
    {
        $this->copyStubs('bundle1');

        $bundle = new Bundle('bundles.bundle1', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle1', true);

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function it_gets_values_for_a_multidimensional_array()
    {
        $this->copyStubs('bundle2');

        $bundle = new Bundle('bundles.bundle2.component2', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle2', true)['component2'];

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function it_gets_values_when_multiple_files_present()
    {
        $this->copyStubs(['bundle1', 'bundle2']);

        $bundle = new Bundle('bundles.bundle2.component1', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle2', true)['component1'];

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_directories()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.bundle3.forum.component3', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle3', true)['forum']['component3'];

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_layed_directories()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.sub-components.bundle4', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle4', true);

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function invalid_bundle_name_returns_empty_array()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.none', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $this->assertEquals([], $values);
    }

    /**
     * @test
     */
    public function it_finds_bundles_with_registered_alias()
    {
        $this->copyStubs('bundle2');

        app()['config']->set('lang-bundler.aliases', [
            'test' => 'bundles.bundle2.component1',
        ]);

        $bundle = new Bundle('test', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle2', true)['component1'];

        $this->assertEquals($expected, $values);
    }

    /**
     * @test
     */
    public function it_finds_bundles_from_auto_registered_alias()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundle4', $this->bundleMap);

        $values = $this->bundleMap->getBundleValues($bundle->getPathKeys())->all();

        $expected = $this->getExpected('bundle4', true);

        $this->assertEquals($expected, $values);
    }
}
