<?php

return [
    'path'          => env('JIRACAL_PATH', 'jiracal'),

    'jira_url'              => env('JIRACAL_URL'),
    'guest_jira_username'   => env('JIRACAL_GUEST_USERNAME'),
    'guest_jira_password'   => env('JIRACAL_GUEST_PASSWORD'),

    'meta_title'            => env('JIRACAL_META_TITLE', 'Jira Cal'),
    'meta_author'           => env('JIRACAL_META_AUTHOR'),
    'meta_description'      => env('JIRACAL_META_DESCRIPTION'),
];
