<?php

return [
    'me Child' => [
        'role' => 'me.child',
        'allowed' => [
            'attach.profile.image',
            'can.friend',
            'create.user.flip',
            'edit.user.adult',
            'edit.user.child',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'update.password',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.flip',
        ],
        'denied' => [
            'add.group.user',
            'adult.code',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'edit.group',
            'edit.org',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.user.groups',
            'view.user.orgs',
        ],
    ],
    'me Adult' => [
        'role' => 'me.adult',
        'allowed' => [
            'attach.profile.image',
            'edit.user.adult',
            'edit.user.child',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'update.password',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
        ],
        'denied' => [
            'add.group.user',
            'adult.code',
            'can.friend',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.user.flip',
            'view.user.groups',
            'view.user.orgs'
        ],
    ],
    'Super Admin' => [
        'role'    => 'super',
        'allowed' => [
            'add.group.user',
            'adult.code',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'edit.org',
            'edit.group',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'update.password',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org.users',
            'view.profile.image',
            'view.user.flip',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
            'view.user.orgs'
        ],

        'denied' => [
            'attach.profile.image',
            'can.friend',
            'create.user.flip',
            'save.game',
            'view.org',
        ],
    ],

    'Admin' => [
        'role'    => 'admin',
        'allowed' => [
            'add.group.user',
            'adult.code',
            'child.code',
            'create.child.group',
            'create.group',
            'edit.group',
            'edit.user.child',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group.user',
            'remove.user.adult',
            'remove.user.child',
            'update.password',
            'view.flip',
            'view.org.users',
            'view.profile.image',
            'view.games',
            'view.group',
            'view.group.users',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
            'view.user.orgs'
        ],

        'denied' => [
            'attach.profile.image',
            'can.friend',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.org',
            'edit.user.adult',
            'remove.group',
            'remove.org',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.org',
            'view.user.flip',
        ],
    ],

    'Group Admin' => [
        'role'    => 'group_admin',
        'allowed' => [
            'add.group.user',
            'child.code',
            'edit.group',
            'edit.user.child',
            'pick.username',
            'remove.group.user',
            'remove.user.adult',
            'remove.user.child',
            'update.password',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
            'view.user.orgs'
        ],

        'denied' => [
            'adult.code',
            'attach.profile.image',
            'can.friend',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.org',
            'edit.user.adult',
            'import',
            'remove.child.group',
            'remove.group',
            'remove.org',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.org',
            'view.user.flip',
        ],
    ],

    'Principal' => [
        'role'    => 'principal.adult',
        'allowed' => [
            'add.group.user',
            'adult.code',
            'child.code',
            'create.child.group',
            'create.group',
            'edit.group',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group.user',
            'remove.user.adult',
            'remove.user.child',
            'update.password',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
        ],

        'denied' => [
            'attach.profile.image',
            'can.friend',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.org',
            'edit.user.adult',
            'pick.username',
            'remove.group',
            'remove.org',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.user.flip',
            'view.user.orgs'
        ],
    ],

    'Assistant Principal' => [
        'role'    => 'asst_principal.adult',
        'allowed' => [
            'add.group.user',
            'adult.code',
            'child.code',
            'create.child.group',
            'create.group',
            'edit.group',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group.user',
            'remove.user.adult',
            'remove.user.child',
            'update.password',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
        ],

        'denied' => [
            'attach.profile.image',
            'can.friend',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.org',
            'edit.user.adult',
            'pick.username',
            'remove.group',
            'remove.org',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.user.flip',
            'view.user.groups',
            'view.user.orgs'
        ],
    ],
    'Teacher' => [
        'role'    => 'teacher.adult',
        'allowed' => [
            'add.group.user',
            'child.code',
            'edit.group',
            'edit.user.child',
            'remove.group.user',
            'remove.user.child',
            'update.password',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
        ],

        'denied' => [
            'adult.code',
            'attach.profile.image',
            'can.friend',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.org',
            'edit.user.adult',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.org',
            'remove.user.adult',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.user.flip',
            'view.user.orgs'
        ],
    ],

    'Logged In child' => [
        'role'    => 'logged_in.child',
        'allowed' => [
            'pick.username',
            'update.password',
            'view.flip',
            'view.games',
            'view.group.users',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
        ],

        'denied' => [
            'add.group.user',
            'adult.code',
            'attach.profile.image',
            'can.friend',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.group',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.flip',
            'view.user.orgs',
        ],
    ],

    'Logged In Adult' => [
        'role' => 'logged_in.adult',
        'allowed' => [
            'pick.username',
            'update.password',
            'view.games',
            'view.user.adult',
            'view.user.child',
            'view.user.groups',
            'view.user.orgs',
        ],
        'denied' => [
            'add.group.user',
            'adult.code',
            'attach.profile.image',
            'can.friend',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.flip',
        ],
    ],

    'Neighbor' => [
        'role'    => 'neighbor.adult.adult',
        'allowed' => [
            'adult.code',
            'remove.user.adult',
            'view.profile.image',
            'view.user.adult',
        ],

        'denied' => [
            'add.group.user',
            'attach.profile.image',
            'can.friend',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.child',
            'save.game',
            'update.password',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.user.child',
            'view.user.flip',
            'view.user.groups',
            'view.user.orgs',
        ],
    ],

    'Child' => [
        'role'    => 'child',
        'allowed' => [
            'can.friend',
            'child.code',
            'create.user.flip',
            'pick.username',
            'update.password',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.flip',
        ],

        'denied' => [
            'add.group.user',
            'adult.code',
            'attach.profile.image',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.user.groups',
            'view.user.orgs',
        ],
    ],

    'Student' => [
        'role'    => 'student.child',
        'allowed' => [
            'can.friend',
            'child.code',
            'pick.username',
            'update.password',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.flip',
            'view.user.groups',
        ],

        'denied' => [
            'add.group.user',
            'adult.code',
            'attach.profile.image',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.user.orgs',
        ],
    ],

    'Guest' => [
        'role'    => 'guest',
        'allowed' => [

        ],

        'denied' => [
            'add.group.user',
            'adult.code',
            'attach.profile.image',
            'can.friend',
            'child.code',
            'create.child.group',
            'create.group',
            'create.org',
            'create.user',
            'create.user.flip',
            'edit.group',
            'edit.org',
            'edit.user.adult',
            'edit.user.child',
            'import',
            'pick.username',
            'remove.child.group',
            'remove.group',
            'remove.group.user',
            'remove.org',
            'remove.user.adult',
            'remove.user.child',
            'save.game',
            'update.password',
            'view.all.groups',
            'view.all.orgs',
            'view.all.users',
            'view.flip',
            'view.games',
            'view.group',
            'view.group.users',
            'view.org',
            'view.org.users',
            'view.profile.image',
            'view.user.adult',
            'view.user.child',
            'view.user.flip',
            'view.user.groups',
            'view.user.orgs',
        ],
    ],
];
