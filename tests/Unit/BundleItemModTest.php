<?php

namespace LaravelLangBundler\tests\Unit;

use Artisan;
use LaravelLangBundler\Bundle\Bundle;
use LaravelLangBundler\tests\TestCase;
use LaravelLangBundler\tests\stubs\ExpectedResults;
use LaravelLangBundler\BundleItems\Mods\CallbackMod;

class BundleItemModTest extends TestCase
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

            $this->assertInstanceOf(CallbackMod::class, $value);
        }
    }

    /**
     * @test
     */
    public function parameters_can_still_be_passed_to_transb()
    {
        extract($this->testMod(['user' => 'Bob']));

        $this->assertEquals('Welcome Bob', $values[0]);

        $this->assertEquals('YOU SENT A MESSAGE TO BOB', $values[1]);

        $this->assertEquals('You have an invite from Bob', $values[2]);
    }

    /**
     * @test
     */
    public function mod_callback_transforms_keys()
    {
        extract($this->testMod());

        $this->assertEquals('Welcome_user', $keys[0]);

        $this->assertEquals('message_to', $keys[1]);

        $this->assertEquals('InviteFrom', $keys[2]);
    }

    /**
     * @test
     */
    public function mod_callback_transforms_values()
    {
        extract($this->testMod());

        $this->assertEquals('Welcome :user', $values[0]);

        $this->assertEquals('YOU SENT A MESSAGE TO :USER', $values[1]);

        $this->assertEquals('You have an invite from :user', $values[2]);
    }

    /**
     * @test
     */
    public function mod_callback_transforms_both()
    {
        extract($this->testMod());

        $this->assertEquals('MESSAGE_TO', $keys[5]);

        $this->assertEquals('YOU SENT A MESSAGE TO :USER', $values[5]);
    }

    /**
     * @test
     */
    public function mods_can_be_read_from_app_directory()
    {
        Artisan::call('langb:mod', ['name' => 'test']);

        $path = app_path('LangBundler/Mods/Bin2hexMod.php');

        $stub = file_get_contents(__DIR__.'/../stubs/Bin2hexMod.php');

        file_put_contents($path, $stub);

        $this->copyStubs('bin2hex');

        $this->copyTranslations();

        $bundle = new Bundle('bin2hex', $this->bundleMap);

        $this->translator->translateBundle($bundle);

        $this->assertEquals(
            '77656c636f6d655f75736572',
            $bundle->getValues()[0]->getKey()
        );
    }

    /**
     * @test
     */
    public function mod_change_works()
    {
        extract($this->testMod());

        $this->assertEquals('newKey', $keys[3]);
    }

    /**
     * @test
     */
    public function mod_values_works()
    {
        extract($this->testMod());

        $expected = $this->getExpected('months');

        $this->assertEquals($expected, $values[4]);
    }

    /**
     * @test
     */
    public function mod_ucfirst_works()
    {
        extract($this->testMod());

        $this->assertEquals('Lowercase string', $values[6]);
    }

    /**
     * @test
     */
    public function mod_strtoupper_works()
    {
        extract($this->testMod());

        $this->assertEquals('LOWERCASE STRING', $values[7]);
    }

    /**
     * @test
     */
    public function mod_explode_works()
    {
        extract($this->testMod());

        $this->assertEquals(['lowercase', 'string'], $values[8]);
    }

    /**
     * @test
     */
    public function mod_strtolower_works()
    {
        extract($this->testMod());

        $this->assertEquals('uppersace string', $values[9]);
    }

    /**
     * Run the mod test and return keys and values.
     *
     * @return array
     */
    public function testMod($parameters = [])
    {
        $this->copyStubs('bundle10');

        $this->copyTranslations();

        $bundle = new Bundle('bundle10', $this->bundleMap);

        $this->translator->translateBundle($bundle, $parameters);

        $keys = $bundle->getValues()->map(function ($value) {
            return $value->getKey();
        });

        $values = $bundle->getValues()->map(function ($value) {
            return $value->getValue();
        });

        return ['keys' => $keys, 'values' => $values];
    }
}
