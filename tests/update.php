<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');

$book = $dorm->getBook(1);

/* @var $book Book */
//$book->setTitle('The New Testament');

/**
 * Reset authors !
 */
// $book->authors = array(); // this would completely reset the authors
$book->authors[1] = new Author();
$book->authors[1]->name = 'New Author 1';
$book->authors[2] = new Author();
$book->authors[2]->name = 'New Author 2';

$dorm->save($book);

echo 'Updated book with ID 1 !';
include('footer.php');