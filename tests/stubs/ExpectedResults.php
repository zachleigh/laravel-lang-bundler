<?php

namespace LaravelLangBundler\tests\stubs;

trait ExpectedResults
{
    /**
     * Get expected values for bundles.
     *
     * @param string|array $bundles
     *
     * @return array
     */
    public function getExpected($bundles, $flat = false)
    {
        if (is_string($bundles) && $flat) {
            return $this->{$bundles}()[$bundles];
        }

        if (!is_array($bundles)) {
            $bundles = [$bundles];
        }

        return $this->mergeBundles($bundles);
    }

    /**
     * Merge bundles into single array.
     *
     * @param array $bundles
     *
     * @return array
     */
    private function mergeBundles(array $bundles)
    {
        $expected = [];

        foreach ($bundles as $bundle) {
            $expected = array_merge($expected, $this->{$bundle}());
        }

        return $expected;
    }

    /**
     * Expected results for bundle1 stub.
     *
     * @return array
     */
    private function bundle1()
    {
        return [
            'bundle1' => [
                'home.welcome',
                'home.signup',
                'home.login',
                'nav.home',
                'nav.top',
            ],
        ];
    }

    /**
     * Expected results for bundle2 stub.
     *
     * @return array
     */
    private function bundle2()
    {
        return [
            'bundle2' => [
                'component1' => [
                    'home.welcome',
                    'home.signup',
                    'home.login',
                    'nav.home',
                    'nav.top',
                ],

                'component2' => [
                    'home.welcome',
                    'home.signup',
                    'home.login',
                    'nav.home',
                    'nav.top',
                ],
            ],
        ];
    }

    /**
     * Expected results for bundle3 stub.
     *
     * @return array
     */
    private function bundle3()
    {
        return [
            'bundle3' => [
                'component1' => [
                    'home.welcome',
                    'home.signup',
                    'home.login',
                    'nav.home',
                    'nav.top',
                ],

                'forum' => [
                    'component2' => [
                        'home.welcome',
                        'home.signup',
                        'home.login',
                        'nav.home',
                        'nav.top',
                    ],

                    'component3' => [
                        'payment.creditcard',
                        'payment.date',
                        'payment.submit',
                    ],
                ],
            ],
        ];
    }

    /**
     * Expected results for bundle4 stub.
     *
     * @return array
     */
    private function bundle4()
    {
        return [
            'bundle4' => [
                'home.welcome',
                'home.signup',
                'home.login',
                'nav.home',
                'nav.top',
            ],
        ];
    }

    /**
     * Expected results for English home translations.
     *
     * @return array
     */
    private function homeEn()
    {
        return [
            'welcome' => 'Welcome',
            'signup'  => 'Signup',
            'login'   => 'Login',
        ];
    }

    /**
     * Expected results for English nav translations.
     *
     * @return array
     */
    private function navEn()
    {
        return [
            'home' => 'Home',
            'top'  => 'Top',
        ];
    }

    /**
     * Expected results for English home translations.
     *
     * @return array
     */
    private function homeJa()
    {
        return [
            'welcome' => 'ようこそ',
            'signup'  => 'サインアップ',
            'login'   => 'ログイン',
        ];
    }

    /**
     * Expected results for English nav translations.
     *
     * @return array
     */
    private function navJa()
    {
        return [
            'home' => 'ホーム',
            'top'  => 'トップ',
        ];
    }

    /**
     * Expected results for bundle7 translations.
     *
     * @return array
     */
    private function bundle7()
    {
        return [
            'welcome' => 'Welcome',
            'signup'  => 'Signup',
            'login'   => 'Login',
            'home'    => 'Home',
            'top'     => 'Top',
        ];
    }

    /**
     * Expected results for bundle10 translations.
     *
     * @return array
     */
    private function bundle10()
    {
        return [
            "Welcome_user" => "Welcome :user",
            "message_to" => "YOU SENT A MESSAGE TO :USER",
            "InviteFrom" => "You have an invite from :user",
            "newKey" => "You have an invite from :user",
        ];
    }
}
