<?php namespace MusicCollection\Databases;

/**
 * In a standard situation a model stored in the session will only store publuc promoted properties
 * If there is other information relevant to store, use this attribute for these properties
 * @package MusicCollection\Databases
 */
#[\Attribute]
class Serialized
{

}
