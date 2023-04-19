<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

use JsonSerializable;

final class ObjectToArrayTransformer
{
    public static function transform(object|array $object) : mixed
    {
        if (is_iterable($object)) {
            return self::iterate($object);
        }
        else {
            $result = [];
            if ($object instanceof JsonSerializable) {
                return $object->jsonSerialize();
            }
            if (method_exists($object, 'toArray')) {
                return self::transform($object->toArray());
            }
            if (method_exists($object, '__toString')) {
                return $object->__toString();
            }
            if (method_exists($object, 'toString')) {
                return $object->toString();
            }
            /**
             * @var string[] $vars
             */
            $vars = array_keys(get_object_vars($object));
            foreach ($vars as $var) {
                $result[self::camelToSnake($var)] = self::canTransformFurther($object->$var) ? self::transform($object->$var) : $object->$var;
            }
        }
        return $result;
    }

    /**
     * @param object|array $object
     * @return array
     */
    protected static function iterate(object|array $object) : array
    {
        $result = [];
        foreach ($object as $key => $value) {
            if (!is_numeric($key)) {
                $key = self::camelToSnake($key);
            }
            $result[$key] = self::canTransformFurther($value) ? self::transform($value) : $value;
        }
        return $result;
    }

    private static function camelToSnake(string $string) : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private static function canTransformFurther(mixed $value) : bool
    {
        if (is_object($value)) {
            return true;
        }
        if (is_iterable($value)) {
            return true;
        }
        return false;
    }
}
