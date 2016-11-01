<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\BundleItems\CallbackWrapper;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class BundleItemWrapperTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_creates_bundle_item_classes()
    {
        $this->copyStubs('bundle10');

        $bundle = new Bundle('bundle10', $this->bundleMap);

        foreach ($bundle->getValues() as $key => $value) {
            if ($key > 2) {
                break;
            }

            $this->assertInstanceOf(CallbackWrapper::class, $value);
        }
    }

    /**
     * @test
     */
    public function parameters_can_still_be_passed_to_transb()
    {
        $this->copyStubs('bundle10');

        $this->copyTranslations();

        $bundle = new Bundle('bundle10', $this->bundleMap);

        $this->translator->translateBundle($bundle, [
            'user' => 'Bob',
        ]);

        $values = $bundle->getValues()->map(function ($value) {
            return $value->getValue();
        });

        $this->assertEquals('Welcome Bob', $values[0]);

        $this->assertEquals('YOU SENT A MESSAGE TO BOB', $values[1]);

        $this->assertEquals('You have an invite from Bob', $values[2]);
    }

    /**
     * @test
     */
    public function wrapper_callback_transforms_keys()
    {
        $this->copyStubs('bundle10');

        $bundle = new Bundle('bundle10', $this->bundleMap);

        $keys = $bundle->getValues()->map(function ($value) {
            return $value->getKey();
        });

        $this->assertEquals('Welcome_user', $keys[0]);

        $this->assertEquals('message_to', $keys[1]);

        $this->assertEquals('InviteFrom', $keys[2]);
    }

    /**
     * @test
     */
    public function wrapper_callback_transforms_values()
    {
        $this->copyStubs('bundle10');

        $this->copyTranslations();

        $bundle = new Bundle('bundle10', $this->bundleMap);

        $this->translator->translateBundle($bundle);

        $values = $bundle->getValues()->map(function ($value) {
            return $value->getValue();
        });

        $this->assertEquals('Welcome :user', $values[0]);

        $this->assertEquals('YOU SENT A MESSAGE TO :USER', $values[1]);

        $this->assertEquals('You have an invite from :user', $values[2]);
    }

    /**
     * @test
     */
    public function wrapper_change_changes_key()
    {
        $this->copyStubs('bundle10');

        $this->copyTranslations();

        $bundle = new Bundle('bundle10', $this->bundleMap);

        $this->translator->translateBundle($bundle);

        $keys = $bundle->getValues()->map(function ($value) {
            return $value->getKey();
        });

        $this->assertEquals('newKey', $keys[3]);
    }
}
