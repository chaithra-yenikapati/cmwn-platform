<?php
/**
 * @codingStandardsIgnoreStart
 */
return [
    'flips'         => [],
    'organizations' => [
        [
            'org_id'      => 'district',
            'title'       => 'Gina\'s District',
            'description' => null,
            'meta'        => null,
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'type'        => 'district',
        ],
        [
            'org_id'      => 'manchuck',
            'title'       => 'MANCHUCK\'S district',
            'description' => null,
            'meta'        => null,
            'created'     => '2016-04-27 10:48:44',
            'updated'     => '2016-04-27 10:48:46',
            'deleted'     => null,
            'type'        => 'district',
        ],
    ],
    'games'         => [],
    'groups'        => [
        [
            'group_id'        => 'other_school',
            'organization_id' => 'manchuck',
            'title'           => 'MC School',
            'description'     => null,
            'meta'            => null,
            'head'            => '1',
            'tail'            => '4',
            'created'         => '2016-04-15 15:52:36',
            'updated'         => '0000-00-00 00:00:00',
            'deleted'         => null,
            'type'            => 'school',
            'external_id'     => null,
            'parent_id'       => null,
            'depth'           => '0',
            'network_id'      => 'other_school',
        ],
        [
            'group_id'        => 'school',
            'organization_id' => 'district',
            'title'           => 'Gina\'s School',
            'description'     => null,
            'meta'            => null,
            'head'            => '1',
            'tail'            => '6',
            'created'         => '2016-04-15 15:46:07',
            'updated'         => '0000-00-00 00:00:00',
            'deleted'         => null,
            'type'            => 'school',
            'external_id'     => null,
            'parent_id'       => null,
            'depth'           => '0',
            'network_id'      => 'school',
        ],
        [
            'group_id'        => 'english',
            'organization_id' => 'district',
            'title'           => 'English Class',
            'description'     => null,
            'meta'            => null,
            'head'            => '4',
            'tail'            => '5',
            'created'         => '2016-04-15 15:47:02',
            'updated'         => '0000-00-00 00:00:00',
            'deleted'         => null,
            'type'            => 'class',
            'external_id'     => null,
            'parent_id'       => 'school',
            'depth'           => '1',
            'network_id'      => 'school',
        ],
        [
            'group_id'        => 'math',
            'organization_id' => 'district',
            'title'           => 'Math Class',
            'description'     => null,
            'meta'            => null,
            'head'            => '2',
            'tail'            => '3',
            'created'         => '2016-04-15 15:46:03',
            'updated'         => '0000-00-00 00:00:00',
            'deleted'         => null,
            'type'            => 'class',
            'external_id'     => null,
            'parent_id'       => 'school',
            'depth'           => '1',
            'network_id'      => 'school',
        ],
        [
            'group_id'        => 'other_math',
            'organization_id' => 'manchuck',
            'title'           => 'MC Math',
            'description'     => null,
            'meta'            => null,
            'head'            => '2',
            'tail'            => '3',
            'created'         => '2016-04-15 15:53:06',
            'updated'         => '0000-00-00 00:00:00',
            'deleted'         => null,
            'type'            => 'class',
            'external_id'     => null,
            'parent_id'       => 'other_school',
            'depth'           => '1',
            'network_id'      => 'school',
        ],
    ],
    'images'        => [],
    'names'         => [],
    'user_flips'    => [],
    'users'         => [
        [
            'user_id'      => 'english_student',
            'username'     => 'english_student',
            'email'        => 'english_student@ginasink.com',
            'code'         => null,
            'type'         => 'CHILD',
            'password'     => password_hash('business', PASSWORD_DEFAULT),
            'first_name'   => 'John',
            'middle_name'  => 'D',
            'last_name'    => 'Yoder',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:58:15',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'englishstudent'
        ],
        [
            'user_id'      => 'english_teacher',
            'username'     => 'english_teacher',
            'email'        => 'english_teacher@ginasink.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => password_hash('business', PASSWORD_DEFAULT),
            'first_name'   => 'Angelot',
            'middle_name'  => 'M',
            'last_name'    => 'Fredickson',
            'gender'       => 'M',
            'meta'         => '[]',
            'birthdate'    => '2016-04-15 00:00:00',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'englishteacher'
        ],
        [
            'user_id'      => 'math_student',
            'username'     => 'math_student',
            'email'        => 'math_student@ginasink.com',
            'code'         => 'pqr',
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
            'code_expires' => (string)(new \DateTime('-3 days'))->format("Y-m-d H:i:s"),
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'mathstudent'
        ],
        [
            'user_id'      => 'math_teacher',
            'username'     => 'math_teacher',
            'email'        => 'math_teacher@ginasink.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'William',
            'middle_name'  => 'T',
            'last_name'    => 'West',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:50:05',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'mathteacher'
        ],
        [
            'user_id'      => 'other_principal',
            'username'     => 'other_principal',
            'email'        => 'other_principal@manchuck.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Max',
            'middle_name'  => 'C',
            'last_name'    => 'Rafferty',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:51:42',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'otherprincipal'
        ],
        [
            'user_id'      => 'other_student',
            'username'     => 'other_student',
            'email'        => 'other_student@manchuck.com',
            'code'         => null,
            'type'         => 'CHILD',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Chuck',
            'middle_name'  => 'C',
            'last_name'    => 'Reeves',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:51:42',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'otherstudent'
        ],
        [
            'user_id'      => 'other_teacher',
            'username'     => 'other_teacher',
            'email'        => 'other_teacher@manchuck.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Josh',
            'middle_name'  => 'C',
            'last_name'    => 'Savino',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:51:42',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'otherteacher'
        ],
        [
            'user_id'      => 'principal',
            'username'     => 'principal',
            'email'        => 'principal@ginasink.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Kirk',
            'middle_name'  => 'S',
            'last_name'    => 'West',
            'gender'       => 'M',
            'meta'         => null,
            'birthdate'    => '2016-04-15 11:49:08',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '0',
            'external_id'  => null,
            'normalized_username' => 'principal'
        ],
        [
            'user_id'      => 'super_user',
            'username'     => 'super_user',
            'email'        => 'super@ginasink.com',
            'code'         => null,
            'type'         => 'ADULT',
            'password'     => '$2y$10$b53JWhhPjSyHvbvaL0aaD.9G3RKTd4pZn6JCkop6pkqFYDrEPJTC.',
            'first_name'   => 'Joni',
            'middle_name'  => null,
            'last_name'    => 'Albers',
            'gender'       => 'F',
            'meta'         => null,
            'birthdate'    => '2016-04-27 10:48:42',
            'created'      => '2016-04-27 10:48:44',
            'updated'      => '2016-04-27 10:48:46',
            'deleted'      => null,
            'code_expires' => null,
            'super'        => '1',
            'external_id'  => null,
            'normalized_username' => 'superuser'
        ],
    ],
    'user_friends'  => [],
    'user_groups'   => [
        [
            'user_id'  => 'english_student',
            'group_id' => 'english',
            'role'     => 'student',
        ],
        [
            'user_id'  => 'english_teacher',
            'group_id' => 'english',
            'role'     => 'teacher',
        ],
        [
            'user_id'  => 'math_student',
            'group_id' => 'math',
            'role'     => 'student',
        ],
        [
            'user_id'  => 'math_teacher',
            'group_id' => 'math',
            'role'     => 'teacher',
        ],
        [
            'user_id'  => 'other_teacher',
            'group_id' => 'other_math',
            'role'     => 'teacher',
        ],
        [
            'user_id'  => 'other_student',
            'group_id' => 'other_math',
            'role'     => 'student',
        ],
        [
            'user_id'  => 'other_principal',
            'group_id' => 'other_school',
            'role'     => 'principal',
        ],
        [
            'user_id'  => 'principal',
            'group_id' => 'school',
            'role'     => 'principal',
        ],
    ],
    'user_images'   => [],
    'user_saves'    => [],
];
