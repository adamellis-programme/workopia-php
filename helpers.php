<?php


/**
 * Get the base path
 *
 * takes in a path
 * @param string $path
 * @return string
 */

function basePath($path = '')
{
    // gets us the absolute path __DIR__
    echo __DIR__;
    return __DIR__ . '/' . $path;
}
