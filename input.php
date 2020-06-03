<?php

/**
 * achieved get user input like php -a
 */
while ('exit' != ($str = fgets(STDIN))) {
    try {
        eval($str . ';');
        if (strpos($str, 'echo') === 0) {
            echo PHP_EOL;
        }
    } catch (\Error $e) {
        print_r($e->getMessage());
        echo PHP_EOL;
    }
}
