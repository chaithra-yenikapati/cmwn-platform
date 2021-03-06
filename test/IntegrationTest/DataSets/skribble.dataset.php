<?php

return [

    'organizations' => [],





    'users'         => [
        [
            'user_id'      => 'english_student',
            'username'     => 'english_student',
            'email'        => 'english_student@ginasink.com',
            'code'         => null,
            'type'         => 'CHILD',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'John',
            'middle_name'  => 'D',
            'last_name'    => 'Yoder',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:58:15',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'super'        => '0',
            'external_id'  => null,
        ],
        [
            'user_id'      => 'math_student',
            'username'     => 'math_student',
            'email'        => 'math_student@ginasink.com',
            'code'         => null,
            'type'         => 'CHILD',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'WILLIS',
            'middle_name'  => 'C',
            'last_name'    => 'KELSEY',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:50:47',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'super'        => '0',
            'external_id'  => null,
        ],
        [
            'user_id'      => 'manchuck',
            'username'     => 'manchuck',
            'email'        => 'chuck@manchuck.com',
            'code'         => null,
            'type'         => 'CHILD',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Chuck',
            'middle_name'  => 'H',
            'last_name'    => 'Reeves',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '1982-05-13 11:36:00', // Yes that is my real birthdate and time
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'super'        => '0',
            'external_id'  => null,
        ],
    ],



    'skribbles'     => [
        // draft for english_student
        [
            'skribble_id' => 'foo-bar',
            'version'     => '1',
            'url'         => 'https://media.changemyworldnow.com/f/abcdef',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'status'      => 'NOT_COMPLETE',
            'created_by'  => 'english_student',
            'friend_to'   => null,
            'read'        => 0,
            'rules'       => '[]',
        ],
        // sent but processing for english_student
        [
            'skribble_id' => 'qux-thud',
            'version'     => '1',
            'url'         => 'https://media.changemyworldnow.com/f/abcdef',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'status'      => 'PROCESSING',
            'created_by'  => 'english_student',
            'friend_to'   => 'math_student',
            'read'        => 0,
            'rules'       => '[]',
        ],
        // sent to math_student and received for math_student
        [
            'skribble_id' => 'baz-bat',
            'version'     => '1',
            'url'         => 'https://media.changemyworldnow.com/f/abcdef',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'status'      => 'COMPLETE',
            'created_by'  => 'english_student',
            'friend_to'   => 'math_student',
            'read'        => 0,
            'rules'       => '[]',
        ],
        // sent by manchuck
        [
            'skribble_id' => 'fizz-buzz',
            'version'     => '1',
            'url'         => 'https://media.changemyworldnow.com/f/abcdef',
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'status'      => 'COMPLETE',
            'created_by'  => 'manchuck',
            'friend_to'   => 'math_student',
            'read'        => 1,
            'rules'       => '[]',
        ],
    ],
];
