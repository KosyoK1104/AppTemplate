<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

use JsonSerializable;
use Traversable;

final class ObjectToArrayTransformer
{
    public static function transform(object|array $object) : mixed
    {
        $result = [];
        if (is_array($object)) {
            return self::iterate($object);
        }
        if ($object instanceof JsonSerializable) {
            return $object->jsonSerialize();
        }
        if ($object instanceof Traversable) {
            return self::iterate($object);
        }
        if (method_exists($object, 'toArray')) {
            return $object->toArray();
        }
        if (method_exists($object, '__toString')) {
            return $object->__toString();
        }
        /**
         * @var string[] $vars
         */
        $vars = array_keys(get_object_vars($object));
        foreach ($vars as $var) {
            $result[self::camelToSnake($var)] = self::canTransformFurther($object->$var) ? self::transform($object->$var) : $object->$var;
        }
        return $result;
    }

    private static function camelToSnake(string $string) : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    private static function iterate(iterable $iteratable) : array
    {
        $result = [];
        foreach ($iteratable as $key => $value) {
            if (!is_numeric($key)) {
                $key = self::camelToSnake($key);
            }
            $result[$key] = self::canTransformFurther($value) ? self::transform($value) : $value;
        }
        return $result;
    }

    private static function canTransformFurther(mixed $value) : bool
    {
        if (is_object($value)) {
            return true;
        }
        if (is_array($value)) {
            return true;
        }
        return false;
    }
}
