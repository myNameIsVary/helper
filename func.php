<?php
/**
 * helper functions in the work
 */

/**
 * padding zero before the number
 *
 * @param int $number
 * @param int $length
 * @return string
 * @author : ren 846723340@qq.com
 * @modify : 2020-06-04 01:15:15
 */
function paddingZero(int $number, int $length): string
{
    if (strlen($number) >= $length) {
        return $number;
    }

    return substr(pow(10, $length) + $number, 1);
}