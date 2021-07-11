<?php

/**
 * @file
 * Defines a function wrapper for HTML Purifier for quick use.
 * @note ''HTMLPurifier()'' is NOT the same as ''new HTMLPurifier()''
 */

/**
 * Purify HTML.
 * @param string $html String HTML to purify
 * @param mixed $config Configuration to use, can be any value accepted by
 *        HTMLPurifier_Config::create()
 * @return string
 */
function HTMLPurifierX($html, $config = null)
{
    static $purifier = false;
    if (!$purifier) {
        $purifier = new HTMLPurifierX();
    }
    return $purifier->purify($html, $config);
}

