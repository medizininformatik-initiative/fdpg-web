<?php

include 'bootstrap.php';

use ACPT\Includes\ACPT_DB;
use ACPT\Includes\ACPT_Schema_Manager;

/**
 * Reset ACPT schema
 */
function resetSchema()
{
    ACPT_Schema_Manager::down();
    ACPT_DB::createSchema();
    ACPT_DB::sync();
}

echo '***********************************************' . PHP_EOL;
echo '* RESETTING DB                                *' . PHP_EOL;
echo '***********************************************' . PHP_EOL;

try {
    resetSchema();
    echo "Done!";
} catch (\Exception $exception){
    echo "ERROR: " . $exception->getMessage();
}

echo PHP_EOL;
echo PHP_EOL;