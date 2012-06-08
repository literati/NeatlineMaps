<?php
/**
 * Bootstrap for NealtineMaps plugin test runner
 */
if (!($omekaDir = getenv('OMEKA_DIR'))) {
    $omekaDir = dirname(dirname(dirname(dirname(__FILE__))));
}

require_once $omekaDir . '/application/tests/bootstrap.php';
require_once 'NeatlineMaps_Test_AppTestCase.php';
