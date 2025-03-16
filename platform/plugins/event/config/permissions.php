<?php

return [
    [
        'name' => 'Events',
        'flag' => 'event.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'event.create',
        'parent_flag' => 'event.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'event.edit',
        'parent_flag' => 'event.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'event.destroy',
        'parent_flag' => 'event.index',
    ],
];
