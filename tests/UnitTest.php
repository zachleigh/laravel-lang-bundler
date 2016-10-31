<?php

namespace LaravelLangBundler\tests;

use LaravelLangBundler\Bundle;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class UnitTest extends TestCase
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

        $bundle = new Bundle('bundles.bundle1');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle1', true);

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_for_a_multidimensional_array()
    {
        $this->copyStubs('bundle2');

        $bundle = new Bundle('bundles.bundle2.component2');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle2', true)['component2'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_when_multiple_files_present()
    {
        $this->copyStubs(['bundle1', 'bundle2']);

        $bundle = new Bundle('bundles.bundle2.component1');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle2', true)['component1'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_directories()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.bundle3.forum.component3');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle3', true)['forum']['component3'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_layed_directories()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.sub-components.bundle4');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle4', true);

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function invalid_bundle_name_returns_empty_array()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundles.components.none');

        $values = $this->bundleMap->setBundleValues($bundle);

        $this->assertEquals([], $values->all());
    }

    /**
     * @test
     */
    public function it_translates_bundle_values()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle2.component1');

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle($bundle);

        $expected = $this->getExpected(['homeEn', 'navEn']);

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function it_translates_bundle_values_with_set_locale()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        app()->setLocale('ja');

        $bundle = new Bundle('bundles.bundle2.component1');

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle($bundle);

        $expected = $this->getExpected(['homeJa', 'navJa']);

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function it_accepts_parameters()
    {
        $this->copyStubs('bundle5');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle5');

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle(
            $bundle,
            ['user' => 'Bob', 'sender' => 'Sally']
        );

        $expected = [
            'welcome_user' => 'Welcome Bob',
            'message_from' => 'You have a message from Sally',
        ];

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function parameters_can_be_namespaced()
    {
        $this->copyStubs('bundle8');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle8');

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle($bundle, [
            'welcome_user.user' => 'Bob',
            'message_to.user'   => 'Sally',
            'invite_from.user'  => 'George',
        ]);

        $expected = [
            'welcome_user' => 'Welcome Bob',
            'message_to'   => 'You sent a message to Sally',
            'invite_from'  => 'You have an invite from George',
        ];

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function it_transforms_keys_to_study_case()
    {
        $expected = [
            'WelcomeUser' => 'Welcome Bob',
            'MessageFrom' => 'You have a message from Sally',
        ];

        $this->transformTest('studly_case', $expected);
    }

    /**
     * @test
     */
    public function it_transforms_keys_to_camel_case()
    {
        $expected = [
            'welcomeUser' => 'Welcome Bob',
            'messageFrom' => 'You have a message from Sally',
        ];

        $this->transformTest('camel_case', $expected);
    }

    /**
     * @test
     */
    public function it_transforms_keys_to_snake_case()
    {
        $expected = [
            'welcome_user' => 'Welcome Bob',
            'message_from' => 'You have a message from Sally',
        ];

        $this->transformTest('snake_case', $expected, 'bundle6');
    }

    /**
     * @test
     */
    public function it_adds_a_key_prefix()
    {
        $this->copyStubs('bundle7');

        $this->copyTranslations();

        app()['config']->set('lang-bundler.global_key_namespace', 'translations');

        $bundle = new Bundle('bundles.bundle7');

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle($bundle);

        $expected = $this->getExpected('bundle7');

        $this->assertEquals($expected, $translations->all());
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

        $bundle = new Bundle('test');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle2', true)['component1'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_finds_bundles_from_auto_registered_alias()
    {
        $this->copyStubs('components');

        $bundle = new Bundle('bundle4');

        $values = $this->bundleMap->setBundleValues($bundle);

        $expected = $this->getExpected('bundle4', true);

        $this->assertEquals($expected, $values->all());
    }

    /**
     * Perform key transformation test.
     *
     * @param string $case
     * @param array  $expected
     * @param string $bundle
     */
    protected function transformTest($case, $expected, $bundleName = 'bundle5')
    {
        $this->copyStubs($bundleName);

        $this->copyTranslations();

        app()['config']->set('lang-bundler.key_transform', $case);

        $bundle = new Bundle('bundles.'.$bundleName);

        $this->bundleMap->setBundleValues($bundle);

        $translations = $this->translator->translateBundle(
            $bundle,
            ['user' => 'Bob', 'sender' => 'Sally']
        );

        $this->assertEquals($expected, $translations->all());
    }
}
