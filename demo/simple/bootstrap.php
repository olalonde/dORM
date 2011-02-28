<?php
/**
 * Add Dorm's library and domain classes in include path
 */
set_include_path(
    dirname(__FILE__) . '/../../lib'
    . PATH_SEPARATOR . dirname(__FILE__) . '/models'
    . PATH_SEPARATOR . get_include_path()
);


/**
 * Register autoload so we don't have to require() and include() every file
 * by hand.
 */
require('Dorm/Loader.php');
Dorm_Loader::registerAutoload();
