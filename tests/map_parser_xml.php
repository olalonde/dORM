<?php
require('bootstrap.php');

$parser = new Dorm_Map_Parser_XML('config/metadata.xml');

$maps = $parser->getMaps();

print_r($maps);