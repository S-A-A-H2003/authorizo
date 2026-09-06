<?php

declare(strict_types=1);

return [

    'routes' => [
        'enabled' => true,
        'prefix' => 'admin',
        'middleware' => ['web', 'authorizo'],
        'name' => 'admin.',
    ],

    'roles' => [
        'admin' => 'admin',
        'user' => 'user',
    ],

];
