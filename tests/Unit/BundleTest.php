<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\BundleItems\BundleItem;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class BundleTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function set_values_sets_a_collection_of_translation_objects()
    {
        $this->copyStubs('bundle2');

        $bundle = new Bundle('bundles.bundle2.component2', $this->bundleMap);

        foreach ($bundle->getValues() as $value) {
            $this->assertInstanceOf(BundleItem::class, $value);
        }
    }

    /**
     * @test
     */
    public function if_value_is_already_a_translation_object_it_passes()
    {
        $this->copyStubs('bundle9');

        $bundle = new Bundle('bundle9', $this->bundleMap);

        foreach ($bundle->getValues() as $value) {
            $this->assertInstanceOf(BundleItem::class, $value);
        }
    }
}
