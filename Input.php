<?php
/**
 * @Tool       : VsCode.
 * @Date       : 2020-03-16 00:50:15
 * @Author     : rxm rxm@wiki361.com
 * @LastEditors: rxm rxm@wiki361.com
 */

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
