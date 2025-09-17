<?php namespace MusicCollection\Utils;

/**
 * Custom class to add some methods on top of PHP Reflection
 * @package MusicCollection\Utils
 */
class Reflection
{
    /**
     * @param object|class-string $objectOrString
     * @return \ReflectionProperty[]
     * @throws \ReflectionException
     */
    public static function getPromotedPublicProperties(object|string $objectOrString): array
    {
        $publicProperties = new \ReflectionClass($objectOrString)->getProperties(\ReflectionProperty::IS_PUBLIC);
        return array_filter($publicProperties, fn (\ReflectionProperty $property) => $property->isPromoted());
    }
}
