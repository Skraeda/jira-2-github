<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import log</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 py-5">
                <div class="card">
                    <div class="card-header">
                        <h1>Import Log</h1>
                    </div>
                    <div class="card-body">
                        <pre>
<?php

echo 'Loading config <br>';
require 'vendor/autoload.php';
$config = require(__DIR__ . '/config.php');

$githubdata = $config['github-data'];
$userNameMapping = $config['username-map'];
$milestoneMapping = $config['milestone-map'];
$labelMapping = $config['label-map'];

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use League\HTMLToMarkdown\HtmlConverter;
use GuzzleHttp\Exception\RequestException;

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['type'] == 'text/xml') {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br><br>";

        $content = file_get_contents($file['tmp_name']);

        $xml = new SimpleXMLElement($content);
        
        $export = [];
        $converter = new HtmlConverter();
        foreach ($xml->channel->item as $issue) {
            if ($issue->type != 'Epic') {
                echo "Converting: " . (string) $issue->key . ' ' . (string) $issue->summary . '<br>';
                $temp = [
                    "title" => (string) $issue->summary,
                    "body" => $converter->convert((string) $issue->description),
                    "labels" => []
                ];
                if (array_key_exists((string) $issue->assignee->attributes()->{'username'}, $userNameMapping)) {
                    $temp["assignees"] = [$userNameMapping[(string) $issue->assignee->attributes()->{'username'}]];
                }
                if (array_key_exists((string) $issue->fixVersion, $milestoneMapping)) {
                    $temp["milestone"] = $milestoneMapping[(string) $issue->fixVersion];
                }
                array_push($temp['labels'], (array_key_exists((string) $issue->type, $labelMapping)) ? $labelMapping[(string) $issue->type] : (string) $issue->type);
                array_push($export, $temp);
            } else {
                echo "Skiped Epic Issue! <br>";
            }
        }
        $client = new Client([
            'base_uri' => 'https://api.github.com',
            'timeout' => 2.0
        ]);
        $requests = function ($total) use ($export, $client, $githubdata) {
            foreach ($export as $item) {
                echo 'Requesting: ' . $item['title'] . '<br>';
                yield new Request(
                    'POST',
                    '/repos/' . $githubdata['owner'] . '/' . $githubdata['repo'] . '/issues?access_token=' . $githubdata['token'],
                    ['Content-type' => 'application/json'],
                    json_encode($item)
                );
            }
        };
        $pool = new Pool(
            $client,
            $requests(100),
            [
                'concurrency' => 5,
                'fulfilled' => function (Response $response, $index) {
                    echo $response->getReasonPhrase() . '<br>';
                },
                'rejected' => function (RequestException $reason, $index) {
                    echo $reason->getResponse()->getReasonPhrase() . '<br>';
                    var_dump($reason->getRequest());
                },
            ]
        );

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    } else {
        echo 'The uploaded file is not an xml file.';
    }
} else {
    echo 'No file was uploaded!';
}
?>
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
