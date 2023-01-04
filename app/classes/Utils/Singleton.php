<?php namespace MusicCollection\Utils;

/**
 * Simple interface that can be used to have the same way of working with Singleton pattern
 * @package MusicCollection\Utils
 */
interface Singleton
{
    public static function i(): self;
}
