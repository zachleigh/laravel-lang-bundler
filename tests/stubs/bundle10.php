<?php

return [
    bundleItem('user.welcome_user', 'key_callback', [
        'callback' => 'ucfirst',
    ]),
    bundleItem('user.message_to', 'value_callback', [
        'callback' => 'strtoupper',
    ]),
    bundleItem('user.invite_from', 'key_callback', [
        'callback' => 'studly_case',
    ]),
    bundleItem('user.invite_from', 'key_change', [
        'new' => 'newKey',
    ]),
    bundleItem('home.months', 'value_values'),
    bundleItem('user.message_to', 'both_callback', [
        'callback' => 'strtoupper',
    ]),
];
