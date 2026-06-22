<?php
require_once '/var/shared/vendor/autoload.php';
$client = new MongoDB\Client('mongodb://u07:45SaturnServiceAthens@localhost/u07?authSource=u07');
$database = $client->selectDatabase("u07");
$quotes_collection = $database->selectCollection("quotes");
$random_quote = $quotes_collection->aggregate([['$sample' => ['size' => 1]]])->toArray();
if (!empty($random_quote)) {
    $quote = $random_quote[0];
    $adjective = htmlspecialchars($quote['adjective']);
    $text = htmlspecialchars($quote['quote']);
    $author = htmlspecialchars($quote['author']);
    echo '<div class="my_quote w3-left-align w3-padding">';
    echo "<span class=\"quote-adjective\">A $adjective quote:</span> ";
    echo '"' . $text . '" — ';
    echo $author;
    echo '</div>';
}
?>
