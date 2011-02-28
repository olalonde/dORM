<?php
require('bootstrap.php');

$dorm = new Dorm('config.xml');

$authors = $dorm->getAuthorCollection('author_id <= :aid', array('aid' => 2));

// Dont forget to execute insert first

?>
<p>This load all publishers with an ID smaller or equal to 2.</p>
<?php
echo "<h2>Authors</h2><ul>";

foreach ($authors as $author) {
    echo "<li>{$author->name} ";
    echo "</li>";
}

echo "</ul>";


include('footer.php');