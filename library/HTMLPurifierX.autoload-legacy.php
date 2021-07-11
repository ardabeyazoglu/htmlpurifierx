<?php

/**
 * @file
 * Legacy autoloader for systems lacking spl_autoload_register
 *
 * Must be separate to prevent deprecation warning on PHP 7.2
 */

function __autoload($class)
{
    return HTMLPurifierX_Bootstrap::autoload($class);
}