<?php

namespace LaravelLangBundler\tests\Unit;

use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\BundleItems\BundleItem;
use LaravelLangBundler\BundleItems\ItemFactory;
use LaravelLangBundler\tests\stubs\ExpectedResults;
use LaravelLangBundler\BundleItems\Mods\StrtoupperMod;

class ItemFactoryTest extends TestCase
{
    use ExpectedResults;

    /**
     * @test
     */
    public function it_makes_standard_bundle_item_if_no_type_given()
    {
        $bundleItem = ItemFactory::build('id');

        $this->assertInstanceOf(BundleItem::class, $bundleItem);
    }

    /**
     * @test
     */
    public function it_makes_mod_item_with_value()
    {
        $bundleItem = ItemFactory::build('id', 'value_strtoupper');

        $this->assertInstanceOf(StrtoupperMod::class, $bundleItem);

        $this->assertEquals('value', $bundleItem->getAffect());
    }

    /**
     * @test
     */
    public function it_makes_mod_item_with_key()
    {
        $bundleItem = ItemFactory::build('id', 'key_strtoupper');

        $this->assertInstanceOf(StrtoupperMod::class, $bundleItem);

        $this->assertEquals('key', $bundleItem->getAffect());
    }

    /**
     * @test
     */
    public function it_makes_mod_item_with_both()
    {
        $bundleItem = ItemFactory::build('id', 'both_strtoupper');

        $this->assertInstanceOf(StrtoupperMod::class, $bundleItem);

        $this->assertEquals('both', $bundleItem->getAffect());
    }

    /**
     * @test
     *
     * @expectedException LaravelLangBundler\Exceptions\InvalidModificationTarget
     * @expectedExceptionMessage Target invaild is not allowed. Alowed targets are 'key', 'value', and 'both'.
     */
    public function it_throws_exception_for_invalid_target_value()
    {
        $bundleItem = ItemFactory::build('id', 'invalid_strtoupper');
    }
}
