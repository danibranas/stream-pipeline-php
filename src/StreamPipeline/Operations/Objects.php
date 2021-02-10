<?php


namespace StreamPipeline\Operations;


final class Objects
{
    public static function hasProperty(string $property): callable
    {
        return function ($element) use ($property): bool {
            return property_exists($element, $property);
        };
    }

    public static function hasPropertyWithValue(string $property, $value): callable
    {
        return function ($element) use ($property, $value): bool {
            return property_exists($element, $property) && is_object($element) && $element->$property === $value;
        };
    }

    public static function callMethod(string $method, ...$args): callable
    {
        return function ($element) use ($method, $args) {
            return call_user_func([$element, $method], $args);
        };
    }

    public static function construct(string $classname): callable
    {
        return function ($element) use ($classname) {
            return new $classname($element);
        };
    }

    public static function isInstanceOf(string $classname): callable
    {
        return function ($element) use ($classname): string {
            return $element instanceof $classname;
        };
    }

    public static function castObject(): callable
    {
        return function ($element): object {
            return (object)$element;
        };
    }

    public static function get(string $property): callable
    {
        $getterMethod = self::getGetterName($property);
        return function ($element) use ($getterMethod, $property) {
            if (method_exists($element, $getterMethod)) {
                return $element->$getterMethod();
            }

            return property_exists($element, $property)
                ? $element->$property
                : null;
        };
    }

    private static function getGetterName(string $property): string
    {
        return 'get' . ucfirst($property);
    }
}