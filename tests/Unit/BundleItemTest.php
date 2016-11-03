<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class BundleItemTest extends TestCase
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

        foreach ($bundle->getValues() as $item) {
            $expected = 'translations.'.$item->getNamespace();

            $this->assertEquals($expected, $item->getId());
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

        foreach ($bundle->getValues() as $item) {
            $expected = studly_case($item->getNamespace());

            $this->assertEquals($expected, $item->getKey());
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

        foreach ($bundle->getValues() as $item) {
            $parameters = [
                'welcome_user.user' => 'Bob',
                'message_to.user'   => 'Sally',
                'invite_from.user'  => 'George',
            ];

            $item->setParameters($parameters);

            if (isset($parameters[$item->getKey().'.user'])) {
                $expected = $parameters[$item->getKey().'.user'];

                $this->assertEquals($expected, $item->getParameters()['user']);
            }
        }
    }

    /**
     * @test
     */
    public function it_sets_choice_if_namespaced_choice_value_passed_in_parameters()
    {
        $this->copyStubs('bundle8');

        $this->copyTranslations();

        $bundle = new Bundle('bundles.bundle8', $this->bundleMap);

        $item = $bundle->getValues()[3];

        $item->setParameters([
            'inbox_status.choice' => 3,
        ]);

        $this->assertEquals(3, $item->hasChoice());
    }
}
