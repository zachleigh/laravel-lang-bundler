<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle;
use LaravelLangBundler\Translation;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class TranslationUnitTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_prefixes_keys()
    {
        $this->copyStubs('bundle7');

        $this->copyTranslations();

        app()['config']->set('lang-bundler.global_key_namespace', 'translations');

        $bundle = new Bundle('bundles.bundle7', $this->bundleMap);

        foreach ($bundle->getValues() as $translation) {
            $expected = 'translations.'.$translation->getNamespace();

            $this->assertEquals($expected, $translation->getId());
        }
    }

    /**
     * @test
     */
    public function it_transforms_keys()
    {
        $this->copyStubs('bundle5');

        $this->copyTranslations();

        app()['config']->set('lang-bundler.key_transform', 'studly_case');

        $bundle = new Bundle('bundles.bundle5', $this->bundleMap);

        foreach ($bundle->getValues() as $translation) {
            $expected = studly_case($translation->getNamespace());

            $this->assertEquals($expected, $translation->getKey());
        }
    }

    /**
     * @test
     */
    public function it_ignores_parameters_with_different_namespaces()
    {
        $this->copyStubs('bundle8');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle8', $this->bundleMap);

        foreach ($bundle->getValues() as $translation) {
            $parameters = [
                'welcome_user.user' => 'Bob',
                'message_to.user'   => 'Sally',
                'invite_from.user'  => 'George',
            ];

            $translation->setParameters($parameters);

            $expected = $parameters[$translation->getKey().'.user'];

            $this->assertEquals($expected, $translation->getParameters()['user']);
        }
    }
}
