<?php

namespace dnl_blkv\enum;

use BadMethodCallException;
use dnl_blkv\enum\exception\UndefinedEnumNameException;
use dnl_blkv\enum\exception\UndefinedEnumOrdinalException;
use InvalidArgumentException;
use ReflectionClass;
use Closure;

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
abstract class Enum
{
    /**
     * Error constants.
     */
    const __ERROR_METHOD_NOT_FOUND = 'Method not found in "%s": "%s".';
    const __ERROR_ARGUMENTS_NOT_EMPTY = 'Enum instantiation methods do not accept arguments.';

    /**
     * @var static[]
     */
    protected static $nameToInstanceMapCache = [];

    /**
     * @var static[]
     */
    protected static $ordinalToInstanceMapCache = [];

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
     */
    public static function getFirstByOrdinal(int $ordinal)
    {
        return static::getAllByOrdinal($ordinal)[0];
    }

    /**
     * @param int $ordinal
     *
     * @return static
     * @throws UndefinedEnumOrdinalException When the enum ordinal is undefined.
     */
    public static function getAllByOrdinal(int $ordinal)
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
        static::initialize();

        return static::$ordinalToInstanceMapCache[static::class];
    }

    /**
     */
    protected static function initialize()
    {
        if (!static::isInitialized()) {
            static::initializeEnumMaps();
        }
    }

    /**
     * @return bool
     */
    protected static function isInitialized(): bool
    {
        return isset(static::$ordinalToInstanceMapCache[static::class]);
    }

    /**
     */
    protected static function initializeEnumMaps()
    {
        $enumMapper = new EnumMapper(static::getConstants(), static::getCreateEnumInstanceClosure());
        static::$nameToInstanceMapCache[static::class] = $enumMapper->getNameToInstanceMap();
        static::$ordinalToInstanceMapCache[static::class] = $enumMapper->getOrdinalToInstanceMap();
    }

    /**
     * @return mixed[]
     * ]
     */
    protected static function getConstants(): array
    {
        return static::createSelfReflection()->getConstants();
    }

    /**
     * @return ReflectionClass
     */
    protected static function createSelfReflection(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    /**
     * @return Closure
     */
    protected static function getCreateEnumInstanceClosure(): Closure
    {
        return function (string $name, int $ordinal) {
            return new static($name, $ordinal);
        };
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
        return EnumLib::isSame($this, $other);
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
        if (EnumLib::isValidEnumConstantName($name)) {
            static::assertArgumentsEmpty($arguments);

            return static::getByName($name);
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
    public static function getByName(string $name)
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
     * @return static[]
     */
    protected static function getNameToInstanceMap(): array
    {
        static::initialize();

        return static::$nameToInstanceMapCache[static::class];
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isEqual(Enum $other): bool
    {
        return EnumLib::isEqual($this, $other);
    }

    /**
     * @return int
     */
    public function getOrdinal(): int
    {
        return $this->ordinal;
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isLess(Enum $other): bool
    {
        return EnumLib::isLess($this, $other);
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isLessOrEqual(Enum $other): bool
    {
        return EnumLib::isLessOrEqual($this, $other);
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isGreater(Enum $other): bool
    {
        return EnumLib::isGreater($this, $other);
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function isGreaterOrEqual(Enum $other): bool
    {
        return EnumLib::isGreaterOrEqual($this, $other);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return strval($this);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return EnumLib::toString($this);
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return EnumLib::toJson($this);
    }
}
