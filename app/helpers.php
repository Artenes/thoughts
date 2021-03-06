<?php

/**
 * Slugify a string.
 *
 * @param $string
 * @param array $replace
 * @param string $delimiter
 * @return mixed|string
 * @throws Exception
 */
function slugify($string, $replace = array(), $delimiter = '-')
{

    // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
    if (!extension_loaded('iconv')) {
        throw new Exception('iconv module not loaded');
    }

    // Save the old locale and set the new locale to UTF-8
    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'en_US.UTF-8');
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    if (!empty($replace)) {
        $clean = str_replace((array)$replace, ' ', $clean);
    }
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    $clean = trim($clean, $delimiter);

    // Revert back to the old locale
    setlocale(LC_ALL, $oldLocale);

    return $clean;

}

/**
 * Generates a random avatar.
 *
 * @param null $gender
 * @return string
 */
function rand_avatar($gender = null) {

    $avatar = random_int(1, 99);
    $gender = $gender ? $gender : array_random(['men', 'women']);

    return "https://randomuser.me/api/portraits/{$gender}/{$avatar}.jpg";

}
