<?php
require('bootstrap.php');

$apc = new Dorm_Cache_APC();
$apc->flush();

print_r(apc_cache_info());

$fs = new Dorm_Cache_Filesystem();
$fs->flush();

echo 'cache cleared';