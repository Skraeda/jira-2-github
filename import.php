<?php
require 'vendor/autoload.php';

use SimpleXMLElement;
use League\HTMLToMarkdown\HtmlConverter;

$gitHubData = [
    'owner' => 'Skraeda',
    'repo' => 'quera-manager',
    'token' => 'b5bb92504bdf9cd0e5d23298be9fdbf5f3fb6158'
];

$userNameMapping = [
    'Kristmundur' => 'zenpandan',
    'gunnar' => 'gunsobal',
    'eythork' => 'eythork'
];
  
$milestoneMapping = [
    'Alpha' => 1,
    'Beta' => 2,
    'pre-release' => 3,
    '1.0' => 4,
    '1.1' => 5
];


if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['type'] == 'text/xml') {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";

        $content = file_get_contents($file['tmp_name']);

        $xml = new SimpleXMLElement($content);
        echo '<pre>';
        // var_dump($xml->channel);
        
        $export = [];
        $converter = new HtmlConverter();
        foreach ($xml->channel->item as $issue) {
            $temp = [
                "title" => (string) $issue->summary,
                "body" => $converter->convert((string) $issue->description)
            ];
            if (array_key_exists((string) $issue->assignee->attributes()->{'username'}, $userNameMapping)) {
                $temp["assignees"] = [$userNameMapping[(string) $issue->assignee->attributes()->{'username'}]];
            }
            if (array_key_exists((string) $issue->fixVersion, $milestoneMapping)) {
                $temp["milestone"] = $milestoneMapping[(string) $issue->fixVersion];
            }
            array_push($export, $temp);
        }

        var_dump($export);
    } else {
        echo 'The uploaded file is not an xml file.';
    }
} else {
    echo 'No file was uploaded!';
}
