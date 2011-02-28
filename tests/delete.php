<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');

$book = $dorm->getBook(1);

$dorm->delete($book);
?>

Deleted book.

<h3>THIS WON'T RESET YOUR AUTO_INCREMENT ID. LOAD WON'T WORK EVENTHOUGH YOU INSERT THE BOOK AGAIN. YOU NEED TO <a href="reset_data.php">RESET THE DATABASE</a>.</h3>

<?php
include('footer.php');