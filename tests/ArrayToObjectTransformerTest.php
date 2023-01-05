<?php

declare(strict_types=1);

use App\Shared\Helpers\ObjectToArrayTransformer;
use PHPUnit\Framework\TestCase;

final class ArrayToObjectTransformerTest extends TestCase
{
    public function test_array(): void
    {
        $object = new StdClass();
        $childObject = new StdClass();
        $childObject->barFoo = 'barFoo';
        $object->child = $childObject;
        $object->foo = 'foo';
        $object->fooBar = 'fooBar';
        self::assertEquals(
            [
                'foo' => 'foo',
                'foo_bar' => 'fooBar',
                'child' => [
                    'bar_foo' => 'barFoo'
                ]
            ],
            ObjectToArrayTransformer::transform($object)
        );
    }

    public function test_to_array() : void{
        $object = new class {
            public function toArray() : array {
                return [
                    'foo' => 'foo',
                    'foo_bar' => 'fooBar',
                    'child' => [
                        'bar_foo' => 'barFoo'
                    ]
                ];
            }
        };
        self::assertEquals(
            [
                'foo' => 'foo',
                'foo_bar' => 'fooBar',
                'child' => [
                    'bar_foo' => 'barFoo'
                ]
            ],
            ObjectToArrayTransformer::transform($object)
        );
    }

    public function test_traversable() : void
    {
        $object = new class implements IteratorAggregate {
            public function getIterator() : Traversable
            {
                yield 'foo' => 'foo';
                yield 'fooBar' => 'fooBar';
                yield 'child' => new class implements IteratorAggregate {
                    public function getIterator() : Traversable
                    {
                        yield 'barFoo' => 'barFoo';
                    }
                };
            }
        };
        self::assertEquals(
            [
                'foo' => 'foo',
                'foo_bar' => 'fooBar',
                'child' => [
                    'bar_foo' => 'barFoo'
                ]
            ],
            ObjectToArrayTransformer::transform($object)
        );
    }

    public function test_nested_array_of_different_type_of_objects() : void
    {
        $array = [
            'foo' => 'foo',
            'fooBar' => 'fooBar',
            'child' => [
                'barFoo' => 'barFoo',
                'barFoo2' => new class {
                    public function toArray() : array {
                        return [
                            'foo' => 'foo',
                            'fooBar' => 'fooBar',
                            'child' => [
                                'barFoo' => 'barFoo'
                            ]
                        ];
                    }
                },
                'barFoo3' => new class implements IteratorAggregate {
                    public function getIterator() : Traversable
                    {
                        yield 'foo' => 'foo';
                        yield 'fooBar' => 'fooBar';
                        yield 'child' => new class implements IteratorAggregate {
                            public function getIterator() : Traversable
                            {
                                yield 'barFoo' => 'barFoo';
                            }
                        };
                    }
                }
            ]
        ];
        self::assertEquals(
            [
                'foo' => 'foo',
                'foo_bar' => 'fooBar',
                'child' => [
                    'bar_foo' => 'barFoo',
                    'bar_foo2' => [
                        'foo' => 'foo',
                        'foo_bar' => 'fooBar',
                        'child' => [
                            'bar_foo' => 'barFoo'
                        ]
                    ],
                    'bar_foo3' => [
                        'foo' => 'foo',
                        'foo_bar' => 'fooBar',
                        'child' => [
                            'bar_foo' => 'barFoo'
                        ]
                    ]
                ]
            ],
            ObjectToArrayTransformer::transform($array)
        );
    }
}
