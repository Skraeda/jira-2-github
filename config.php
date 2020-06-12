<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'github-data' => [
        'owner' => $_ENV['owner'],
        'repo' => $_ENV['repo'],
        'token' => $_ENV['token']
    ],
    'username-map' => [
        'jira-user' => 'github-user',
    ],
    'milestone-map' => [
        'beta' => 1,
    ],
    'label-map' => [
        'Bug' => 'bug',
        'New Feature' => 'enhancement',
        'Improvement' => 'enhancement',
    ]
];
