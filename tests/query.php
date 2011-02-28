<?php
/**
 * @todo
 */
require('bootstrap.php');

$query = new Dorm_PDO_Query();

$query->select()->from('')->where('column1 = ?, column2 = ?', 'val1', 'val2')->orderBy('column1 ASC')->execute();