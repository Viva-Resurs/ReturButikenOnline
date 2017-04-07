<?php

/**
 * Returbutiken default settings, only used when setting up the database.
 */

return [
    'settings'=> [
        'users' => [
            'administrator' => [
                'username' => 'admin',
                'fullname' => 'The Admin',
                'phone' => '12345',
                'email'    => 'admin@example.org',
                'password' => 'admin'
            ],
            'sectionadmin' => [
                'username' => 'adam',
                'fullname' => 'Section Chief',
                'phone'    => '12346',
                'email'    => 'adam@example.org',
                'password' => 'adam'
            ]
        ],
        'sections' => [
            'section1' => [
                'name' => 'IT-avdelningen',
                'desc' => 'Returbutikens IT-avdelning'
          ]
        ],
    ]
];
