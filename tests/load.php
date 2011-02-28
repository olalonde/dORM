<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');
//$dorm->flushCache();

try {
    $book = $dorm->getBook(array('id' => 1));
}
catch (Exception $e) {
    echo $e->getMessage();
    echo "\n\nYou might need to run <a href='insert.php'>insert.php</a> first !";
    exit;
}
// Dont forget to execute insert first

echo "<h1>Title: {$book->getTitle()} </h1>";
echo "<h2>Authors</h2><ul>";

foreach ($book->authors as $key => $author) {
    //print_r($author);
    echo "<li>({$key}) {$author->name} ";
    echo isset($author->favoriteAuthor) ? " (favorite author: {$author->favoriteAuthor->name})" : '';
    echo "</li>";
}

echo "</ul>";
echo "<h2>Publishers</h2><ul>";

foreach ($book->publishers as $publisher) {
    echo "<li>{$publisher->name} <ul>
        <li> Location: ({$publisher->location->address} {$publisher->location->city}, {$publisher->location->country})</li></ul>";
    echo "</li>";
}

echo "</ul>";
include('footer.php');