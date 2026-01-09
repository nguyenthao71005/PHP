<?php
// includes/users.php
return [
    'admin' => [
        'hash' => password_hash('123456', PASSWORD_DEFAULT),
        'role' => 'admin'
    ],
    'thao' => [
        'hash' => password_hash('123456', PASSWORD_DEFAULT),
        'role' => 'user'
    ],
];
