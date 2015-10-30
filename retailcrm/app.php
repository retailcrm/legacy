<?php

if (
    function_exists('date_default_timezone_set')
    &&
    function_exists('date_default_timezone_get')
) {
    date_default_timezone_set(@date_default_timezone_get());
}

require_once 'bootstrap.php';

$options = getopt('luce:m:p:r:h:');

if (isset($options['e'])) {
    $command = new Command($options);
    $command->run();
} else {
    CommandHelper::runHelp();
}
