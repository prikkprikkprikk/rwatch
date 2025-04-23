<?php

declare(strict_types=1);

namespace RWatch\Container;

use InvalidArgumentException;

class Container {

    /** @var array<string, class-string|object> */
    protected static array $bindings = [];
    /** @var array<string, object> */
    protected static array $instances = [];

    /**
     * Bind an interface or abstract class to a concrete implementation.
     *
     * @template TAbstract of object
     * @template TConcrete of TAbstract
     * @param class-string<TAbstract> $abstract The abstract class or interface name.
     * @param class-string<TConcrete>|TConcrete $concrete The concrete class name or instance that implements/extends the abstract.
     */
    public static function bind(string $abstract, string|object $concrete): void {
        if (is_object($concrete)) {
            if (!$concrete instanceof $abstract) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Concrete object of class "%s" must implement/extend "%s"',
                        $concrete::class,
                        $abstract
                    )
                );
            }
        } else {
            if (!is_subclass_of($concrete, $abstract)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Concrete class "%s" must implement/extend "%s"',
                        $concrete,
                        $abstract
                    )
                );
            }
        }

        self::$bindings[$abstract] = $concrete;
    }

    /**
     * Get a singleton instance of a class.
     *
     * @template T of object
     * @param class-string<T> $className
     * @return T
     */
    public static function singleton(string $className): object {
        if (!isset(self::$instances[$className])) {
            if (is_string(self::$bindings[$className])) {
                self::$instances[$className] = new self::$bindings[$className];
            } else {
                self::$instances[$className] = self::$bindings[$className];
            }
        }
        /** @var T */
        return self::$instances[$className];
    }

    public static function reset(): void {
        self::$instances = [];
        self::$bindings = [];
    }
}