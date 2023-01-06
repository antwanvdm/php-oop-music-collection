<?php namespace MusicCollection\Utils;

/**
 * Class URL
 * @package MusicCollection\Utils
 */
class URL
{
    /**
     * @return string
     */
    public static function getCurrentPath(string $fullUrl): string
    {
        $fullPath = strtok($fullUrl, '?');

        return substr($fullPath, strlen(BASE_PATH));
    }
}
