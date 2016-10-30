<?php

namespace LaravelLangBundler\tests;

use LaravelLangBundler\tests\stubs\ExpectedResults;

class UnitTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_doesnt_map_bundles_when_bundles_not_present()
    {
        $map = $this->bundler->mapBundles();

        $this->assertEquals([], $map);
    }

    /**
     * @test
     */
    public function it_maps_a_single_dimension_array()
    {
        $this->copyStubs('bundle1');

        $map = $this->bundler->mapBundles();

        $expected = $this->getExpected('bundle1');

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_maps_a_multidimensional_array()
    {
        $this->copyStubs('bundle2');

        $map = $this->bundler->mapBundles();

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

        $map = $this->bundler->mapBundles();

        $expected = $this->getExpected($bundles);

        $this->assertEquals($expected, $map);
    }

    /**
     * @test
     */
    public function it_maps_layered_directories()
    {
        $this->copyStubs('components');

        $map = $this->bundler->mapBundles();

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

        $values = $this->bundler->getBundleValues('bundles.bundle1');

        $expected = $this->getExpected('bundle1', true);

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_for_a_multidimensional_array()
    {
        $this->copyStubs('bundle2');

        $values = $this->bundler->getBundleValues('bundles.bundle2.component2');

        $expected = $this->getExpected('bundle2', true)['component2'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_when_multiple_files_present()
    {
        $this->copyStubs(['bundle1', 'bundle2']);

        $values = $this->bundler->getBundleValues('bundles.bundle2.component1');

        $expected = $this->getExpected('bundle2', true)['component1'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_directories()
    {
        $this->copyStubs('components');

        $values = $this->bundler->getBundleValues('bundles.components.bundle3.forum.component3');

        $expected = $this->getExpected('bundle3', true)['forum']['component3'];

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function it_gets_values_from_within_layed_directories()
    {
        $this->copyStubs('components');

        $values = $this->bundler->getBundleValues('bundles.components.sub-components.bundle4');

        $expected = $this->getExpected('bundle4', true);

        $this->assertEquals($expected, $values->all());
    }

    /**
     * @test
     */
    public function invalid_bundle_name_returns_empty_array()
    {
        $this->copyStubs('components');

        $values = $this->bundler->getBundleValues('bundles.components.none');

        $this->assertEquals([], $values->all());
    }

    /**
     * @test
     */
    public function it_translates_bundle_values()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        $values = $this->bundler->getBundleValues('bundles.bundle2.component1');

        $translations = $this->translator->translateBundle($values);

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

        $values = $this->bundler->getBundleValues('bundles.bundle2.component1');

        $translations = $this->translator->translateBundle($values);

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

        $values = $this->bundler->getBundleValues('bundles.bundle5');

        $translations = $this->translator->translateBundle(
            $values,
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
     * Perform key transformation test.
     *
     * @param string $case
     * @param array  $expected
     */
    protected function transformTest($case, $expected, $bundle = 'bundle5')
    {
        $this->copyStubs($bundle);

        $this->copyTranslations();

        app()['config']->set('lang-bundler.key_transform', $case);

        $values = $this->bundler->getBundleValues('bundles.'.$bundle);

        $translations = $this->translator->translateBundle(
            $values,
            ['user' => 'Bob', 'sender' => 'Sally']
        );

        $this->assertEquals($expected, $translations->all());
    }
}
