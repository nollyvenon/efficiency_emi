<?php

return [
    [
        'name' => 'Home',
        'flag' => 'applicants.index',
        'parent_flag' => 'core.system',
    ],
    [
        'name' => 'Create',
        'flag' => 'applicants.create',
        'parent_flag' => 'applicants.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'applicants.edit',
        'parent_flag' => 'applicants.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'applicants.delete',
        'parent_flag' => 'applicants.index',
    ],
    [
        'name' => 'Assign',
        'flag' => 'applicants.assign',
        'parent_flag' => 'applicants.index',
    ],
    [
        'name' => 'Transfer',
        'flag' => 'applicants.transfer',
        'parent_flag' => 'applicants.index',
    ],
];
