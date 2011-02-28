<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');

$book = new Book();

$book->setTitle('The Bible');

$author1 = new Author();
$author1->name = 'Moses';

$author2 = new Author();
$author2->name = 'John';

$author3 = new Author();
$author3->name = 'Jeremhia';

//$author2->favoriteAuthor = $author3;

$book->authors = array('key1' => $author1, 'key2' => $author2, 'custom-key' => $author3);

$publisher = new Publisher();
$publisher->name = 'Vatican Press';

$publisher->location = new Location();
$publisher->location->address = 666;
$publisher->location->street = 'Church Street';
$publisher->location->city = 'Vatican City';
$publisher->location->country = 'Vatican';

$secondary_publisher = new Publisher();
$secondary_publisher->name = 'Canadian Bible Society';
$secondary_publisher->location = new Location();
$secondary_publisher->location->city = 'Montreal';
$secondary_publisher->location->country = 'Quebec';

$book->publishers = array();
$book->publishers[] = $publisher;
$book->publishers[] = $secondary_publisher;

// SAVE BOOK
$dorm->save($book);

echo 'Book was saved.';

include('footer.php');