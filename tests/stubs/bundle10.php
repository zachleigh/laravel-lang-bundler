<?php

return [
    bundle_item('user.welcome_user', 'key_callback', [
        'callback' => 'ucfirst',
    ]),
    bundle_item('user.message_to', 'value_callback', [
        'callback' => 'strtoupper',
    ]),
    bundle_item('user.invite_from', 'key_callback', [
        'callback' => 'studly_case',
    ]),
    bundle_item('user.invite_from', 'key_change', [
        'new' => 'newKey',
    ]),
    bundle_item('home.months', 'value_values'),
    bundle_item('user.message_to', 'both_callback', [
        'callback' => 'strtoupper',
    ]),
    bundle_item('home.lowercase', 'value_ucfirst'),
    bundle_item('home.lowercase', 'value_strtoupper'),
    bundle_item('home.lowercase', 'value_explode', [
        'delimiter' => ' ',
    ]),
    bundle_item('home.uppercase', 'value_strtolower'),
];
