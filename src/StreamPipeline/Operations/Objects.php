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

    /**
     * Gets a property from an object or array, or null otherwise. If $useGetter is enabled, it will try to use first
     * a matching getter method.
     * @param string $property the property name.
     * @param bool $useGetter if true (default), it will try to use a properly getter method first if it exists.
     * @return callable a callable function.
     */
    public static function get(string $property, bool $useGetter = true): callable
    {
        $getterMethod = self::getGetterName($property);
        return function ($element) use ($getterMethod, $property, $useGetter) {
            if (is_array($element)) {
                return $element[$property] ?? null;
            }

            if ($useGetter && method_exists($element, $getterMethod)) {
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
