![alt text](./logo.png "Jira 2 github")
# Jira to Github issue migration tool
This tool accepts xml containing exported issues from Jira and imports them to your Github repository using the Github API.

## Requirements
PHP 7.2 or higher.

## Installation
1. Just clone the repo to a local directory.
2. Set up the environment variables in the .env file.
3. Serve the application using the built in php server by running the following command in your terminal from the project directory:
```
php -S localhost:8000
```
4. Navigate to http://localhost:8000 to import your issues.

## Configuration
Assignees can be mapped between Jira and Github. Leave empty if you do not want to migrate the Assignees.
```
'username-map' => [
    'jira-username' => 'github-username',
],
```

Releases in Jira Projects can be mapped to Milestones in the Github Repository. You need to map the name of the Release from Jira to the id of the Milestone in your Github repository. Leave the milestone-map empty if you do not want to migrate the Releases.
```
'milestone-map' => [
    'beta' => 1,
    'v1.0' => 2,
],
```

Issue types in Jira will be mapped to Github issue labels. Map the name of the issue type in Jira to the name of the lable in your Github repository. Issue types that are not mapped will be migrated as is to your Github repository.
```
'label-map' => [
    'Bug' => 'bug',
    'New Feature' => 'enhancement',
    'Improvement' => 'enhancement',
]
```