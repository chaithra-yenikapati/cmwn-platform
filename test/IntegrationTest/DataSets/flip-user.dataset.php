<?php

return [
    'flips'            => [
        [
            'flip_id'     => 'manchuck-farmville',
            'description' => 'Become an Awesome Farmer!',
            'title'       => 'Be an Awesome Farmer',
        ],
    ],





    'users'            => [
        [
            'user_id'     => 'needs_acknowledge',
            'username'    => 'needs_acknowledge',
            'email'       => 'needs_acknowledge@ginasink.com',
            'code'        => null,
            'type'        => 'CHILD',
            'password'    => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'  => 'Chuck',
            'middle_name' => '',
            'last_name'   => 'Reeves',
            'gender'      => 'F',
            'meta'        => null,
            'birthdate'   => '2016-04-15 11:58:15',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'super'       => '0',
            'external_id' => '8675309',
        ],
        [
            'user_id'     => 'already_acknowledge',
            'username'    => 'already_acknowledge',
            'email'       => 'already_acknowledge@ginasink.com',
            'code'        => null,
            'type'        => 'CHILD',
            'password'    => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'  => 'Adam',
            'middle_name' => '',
            'last_name'   => 'Walzer',
            'gender'      => 'F',
            'meta'        => null,
            'birthdate'   => '2016-04-15 11:58:15',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'super'       => '0',
            'external_id' => '8675309',
        ],
        [
            'user_id'     => 'no_flips',
            'username'    => 'no_flips',
            'email'       => 'no_flips@ginasink.com',
            'code'        => null,
            'type'        => 'CHILD',
            'password'    => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'  => 'Joni',
            'middle_name' => '',
            'last_name'   => 'Albers',
            'gender'      => 'F',
            'meta'        => null,
            'birthdate'   => '2016-04-15 11:58:15',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'super'       => '0',
            'external_id' => '8675309',
        ],
    ],
    'user_flips'       => [
        [
            'flip_id'        => 'manchuck-farmville',
            'user_id'        => 'needs_acknowledge',
            'earned'         => '2016-04-15 11:58:15',
            'acknowledge_id' => 'foo-bar',
        ],
        [
            'flip_id'        => 'manchuck-farmville',
            'user_id'        => 'needs_acknowledge',
            'earned'         => '2016-04-16 11:58:15',
            'acknowledge_id' => 'baz-bat',
        ],
        [
            'flip_id'        => 'manchuck-farmville',
            'user_id'        => 'already_acknowledge',
            'earned'         => '2016-04-15 11:58:15',
            'acknowledge_id' => null,
        ],
    ],





    'user_suggestions' => [],
];
