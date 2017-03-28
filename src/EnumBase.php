<?php

namespace dnl_blkv\enum;

use dnl_blkv\enum\exception\InvalidEnumNameException;
use dnl_blkv\enum\exception\UndefinedEnumNameException;
use dnl_blkv\enum\exception\UndefinedEnumOrdinalException;
use InvalidArgumentException;
use BadMethodCallException;
use ReflectionClass;

/**
 * Base class for custom enums. Enum values are defined as constants.
 *
 * The names of the enum constants MUST be:
 * - PSR-1 compliant (declared in all upper case with underscore separators)
 * - Start with an uppercase letter
 *
 * The values of the enum constants (ordinals) MUST be either int or null, where null means "auto-determine ordinal".
 * The default starting ordinal for the enums is 0.
 *
 * @example const CAT = 0;
 * @example const DOG = null;
 * @example const BIRD = 3;
 */
abstract class EnumBase implements Enum
{
    /**
     * Error constants.
     */
    const __ERROR_METHOD_NOT_FOUND = 'Method not found in "%s": "%s".';
    const __ERROR_ARGUMENTS_NOT_EMPTY = 'Calls to enum instantiation methods must not contain arguments.';

    /**
     * Constants to check whether or not given constant is internal.
     */
    const __CONSTANT_NAME_PREFIX_START = 0;
    const __CONSTANT_NAME_PREFIX_LENGTH = 2;

    /**
     * Default value for enum ordinal.
     */
    const ORDINAL_DEFAULT = 0;

    /**
     * @var static[]
     */
    protected static $nameToInstanceMapCache = [];

    /**
     * @var static[]
     */
    protected static $ordinalToInstanceMapCache = [];

    /**
     * @var int
     */
    protected static $lastOrdinal = null;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $ordinal;

    /**
     * @param string $name
     * @param int $ordinal
     */
    protected function __construct(string $name, int $ordinal)
    {
        $this->name = $name;
        $this->ordinal = $ordinal;
    }

    /**
     * @param int $ordinal
     *
     * @return static
     * @throws UndefinedEnumOrdinalException When the enum ordinal is undefined.
     */
    public static function createFromOrdinal(int $ordinal)
    {
        if (static::isOrdinalDefined($ordinal)) {
            return static::getOrdinalToInstanceMap()[$ordinal];
        } else {
            throw new UndefinedEnumOrdinalException(static::class, $ordinal);
        }
    }

    /**
     * @param int $ordinal
     *
     * @return bool
     */
    public static function isOrdinalDefined(int $ordinal): bool
    {
        return isset(static::getOrdinalToInstanceMap()[$ordinal]);
    }

    /**
     * @return static[]
     */
    protected static function getOrdinalToInstanceMap(): array
    {
        if (!isset(static::$ordinalToInstanceMapCache[static::class])) {
            static::$ordinalToInstanceMapCache[static::class] = static::createOrdinalToInstanceMap();
        }

        return static::$ordinalToInstanceMapCache[static::class];
    }

    /**
     * @return static[]
     */
    protected static function createOrdinalToInstanceMap(): array
    {
        $ordinalToInstanceMap = [];

        foreach (static::getNameToInstanceMap() as $name => $instance) {
            if (!isset($ordinalToInstanceMap[$instance->getOrdinal()])) {
                $ordinalToInstanceMap[$instance->getOrdinal()] = $instance;
            }
        }

        return $ordinalToInstanceMap;
    }

    /**
     * @return static[]
     */
    protected static function getNameToInstanceMap(): array
    {
        if (!isset(static::$nameToInstanceMapCache[static::class])) {
            static::$nameToInstanceMapCache[static::class] = static::createNameToInstanceMap();
        }

        return static::$nameToInstanceMapCache[static::class];
    }

    /**
     * @return static[]
     */
    protected static function createNameToInstanceMap(): array
    {
        static::resetLastOrdinal();

        $nameToOrdinalMap = [];

        foreach (static::createSelfReflection()->getConstants() as $name => $constantValue) {
            if (static::isEnumConstant($name)) {
                static::assertValidEnumConstantName($name);
                $nameToOrdinalMap[$name] = new static($name, static::getNextOrdinal($constantValue));
            }
        }

        return $nameToOrdinalMap;
    }

    /**
     */
    protected static function resetLastOrdinal()
    {
        static::$lastOrdinal = null;
    }

    /**
     * @return ReflectionClass
     */
    protected static function createSelfReflection(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected static function isEnumConstant(string $name): bool
    {
        return !static::isSelfDefinedConstant($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected static function isSelfDefinedConstant(string $name): bool
    {
        return defined('self::' . $name);
    }

    /**
     * @param string $name
     *
     * @throws InvalidEnumNameException When the constant name is invalid.
     */
    protected static function assertValidEnumConstantName(string $name)
    {
        if (!static::isValidEnumConstantName($name)) {
            throw new InvalidEnumNameException($name);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected static function isValidEnumConstantName(string $name): bool
    {
        return static::isPSR1CompliantConstantName($name) && static::isEnumConstantName($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected static function isPSR1CompliantConstantName(string $name): bool
    {
        return strtoupper($name) === $name;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected static function isEnumConstantName(string $name): bool
    {
        return ctype_upper($name[0]);
    }

    /**
     * @param int|null $constantValue
     *
     * @return int
     */
    protected static function getNextOrdinal(int $constantValue = null): int
    {
        if (is_null(static::$lastOrdinal)) {
            static::$lastOrdinal = self::ORDINAL_DEFAULT;
        } elseif (is_null($constantValue)) {
            static::$lastOrdinal++;
        } else {
            static::$lastOrdinal = $constantValue;
        }

        return static::$lastOrdinal;
    }

    /**
     * @return int
     */
    public function getOrdinal(): int
    {
        return $this->ordinal;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isSame(Enum $other): bool
    {
        return $this === $other;
    }

    /**
     * @param string $name
     * @param mixed[] $arguments
     *
     * @return static
     * @throws BadMethodCallException When the method is not found.
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (static::isValidEnumConstantName($name)) {
            static::assertArgumentsEmpty($arguments);

            return static::createFromName($name);
        } else {
            throw new BadMethodCallException(
                sprintf(self::__ERROR_METHOD_NOT_FOUND, static::class, $name)
            );
        }
    }

    /**
     * @param mixed[] $arguments
     *
     * @throws InvalidArgumentException
     */
    protected static function assertArgumentsEmpty(array $arguments)
    {
        if (!empty($arguments)) {
            throw new InvalidArgumentException(self::__ERROR_ARGUMENTS_NOT_EMPTY);
        }
    }

    /**
     * @param string $name
     *
     * @return static
     * @throws UndefinedEnumNameException When the name does not correspond to an existing enum type.
     */
    public static function createFromName(string $name)
    {
        if (static::isNameDefined($name)) {
            return static::getNameToInstanceMap()[$name];
        } else {
            throw new UndefinedEnumNameException(static::class, $name);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function isNameDefined(string $name): bool
    {
        return isset(static::getNameToInstanceMap()[$name]);
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isEqual(Enum $other): bool
    {
        return $this->getOrdinal() === $other->getOrdinal() && static::class === get_class($other);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode(
            [static::class => [$this->name => $this->ordinal]],
            JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_SLASHES
        );
    }
}
