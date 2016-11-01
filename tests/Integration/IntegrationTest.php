<?php

namespace LaravelLangBundler\tests\Integration;

use LaravelLangBundler\LangBundler;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class IntegrationTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function bundles_can_be_accessed_from_app()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        $translations = app(LangBundler::class)->trans('bundles.bundle2.component1');

        $expected = $this->getExpected(['homeEn', 'navEn']);

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function bundles_can_be_accessed_from_helper()
    {
        $this->copyStubs('bundle2');

        $this->copyTranslations();

        $translations = transB('bundles.bundle2.component1');

        $expected = $this->getExpected(['homeEn', 'navEn']);

        $this->assertEquals($expected, $translations->all());
    }

    /**
     * @test
     */
    public function helper_function_with_no_arguments_returns_lang_bundler_class()
    {
        $langBundler = transB();

        $this->assertInstanceOf('LaravelLangBundler\LangBundler', $langBundler);
    }

    /**
     * @test
     */
    public function passing_a_normal_lang_value_to_helper_function_returns_the_translation()
    {
        $this->copyTranslations();

        $translation = transB('home.welcome');

        $this->assertEquals('Welcome', $translation);
    }

    /**
     * @test
     */
    public function parameters_can_be_sent_through_app_instance()
    {
        $this->copyStubs('bundle5');

        $this->copyTranslations();

        $translations = app(LangBundler::class)->trans(
            'bundles.bundle5',
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
    public function helper_function_accepts_parameters()
    {
        $this->copyStubs('bundle5');

        $this->copyTranslations();

        $translations = transB('bundles.bundle5', ['user' => 'Bob', 'sender' => 'Sally']);

        $expected = [
            'welcome_user' => 'Welcome Bob',
            'message_from' => 'You have a message from Sally',
        ];

        $this->assertEquals($expected, $translations->all());
    }
}
