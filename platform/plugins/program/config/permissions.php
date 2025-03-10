<?php

return [
    [
        'name' => 'Programs',
        'flag' => 'program.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'program.create',
        'parent_flag' => 'program.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'program.edit',
        'parent_flag' => 'program.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'program.destroy',
        'parent_flag' => 'program.index',
    ],
];
