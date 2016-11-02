<?php

namespace LaravelLangBundler\tests\Integration;

use LaravelLangBundler\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class TranslatorIntegrationTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_translates_bundle_values()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle2.component1', $this->bundleMap);

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

        $bundle = new Bundle('bundles.bundle2.component1', $this->bundleMap);

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

        $bundle = new Bundle('bundles.bundle5', $this->bundleMap);

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

        $bundle = new Bundle('bundles.bundle8', $this->bundleMap);

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

        $this->assertArraySubset($expected, $translations->all());
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

        $bundle = new Bundle('bundles.bundle7', $this->bundleMap);

        $translations = $this->translator->translateBundle($bundle);

        $expected = $this->getExpected('bundle7');

        $this->assertEquals($expected, $translations->all());
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

        $bundle = new Bundle('bundles.'.$bundleName, $this->bundleMap);

        $translations = $this->translator->translateBundle(
            $bundle,
            ['user' => 'Bob', 'sender' => 'Sally']
        );

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function if_choice_is_not_null_trans_choice_is_run()
    {
        $this->copyStubs('bundle8');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle8', $this->bundleMap);

        $translations = $this->translator->translateBundle(
            $bundle,
            ['user' => 'Bob', 'inbox_status.choice' => 3]
        );

        $this->assertEquals('You have new messages', $translations['inbox_status']);
    }
}
