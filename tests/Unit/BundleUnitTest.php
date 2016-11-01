<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\Bundle;
use LaravelLangBundler\Translation;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;

class BundleUnitTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function set_values_sets_a_collection_of_translation_objects()
    {
        $this->copyStubs('bundle2');

        $bundle = new Bundle('bundles.bundle2.component2', $this->bundleMap);

        $values = $bundle->getValues();

        foreach ($values as $value) {
            $this->assertInstanceOf(Translation::class, $value);
        }
    }
}
