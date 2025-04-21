<?php

declare(strict_types=1);

namespace RWatch\Container;

use RuntimeException;

class Container {

    /** @var array<string, class-string> */
    protected static array $bindings = [];
    /** @var array<string, object> */
    protected static array $instances = [];

    /**
     * Bind an interface or abstract class to a concrete implementation.
     *
     * @template TAbstract of object
     * @template TConcrete of TAbstract
     * @param class-string<TAbstract> $abstract The abstract class or interface name.
     * @param class-string<TConcrete> $concrete The concrete class name that implements/extends the abstract.
     */
    public static function bind(string $abstract, string $concrete): void {
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
            self::$instances[$className] = new self::$bindings[$className];
        }
        /** @var T */
        return self::$instances[$className];
    }

    public static function reset(): void {
        self::$instances = [];
        self::$bindings = [];
    }
}