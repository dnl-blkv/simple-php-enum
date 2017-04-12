<?php

namespace dnl_blkv\enum;

use InvalidArgumentException;

/**
 */
abstract class EnumLib
{
    /**
     * Error constants.
     */
    const ERROR_COMPARING_TO_OTHER_TYPE = 'Only enums of the same type can be compared with %s method.';

    /**
     * Fields for enum serialization.
     */
    const FIELD_TYPE = 'type';
    const FIELD_NAME = 'name';
    const FIELD_ORDINAL = 'ordinal';

    /**
     * @param string $name
     *
     * @return bool
     */
    final public static function isValidEnumConstantName(string $name): bool
    {
        return static::isPSR1CompliantConstantName($name) && static::isEnumConstantName($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final private static function isPSR1CompliantConstantName(string $name): bool
    {
        return strtoupper($name) === $name;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    final private static function isEnumConstantName(string $name): bool
    {
        return ctype_upper($name[0]);
    }


    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isSame(Enum $one, Enum $other): bool
    {
        return $one === $other;
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isEqual(Enum $one, Enum $other): bool
    {
        return static::isSameType($one, $other) && $one->getOrdinal() === $other->getOrdinal();
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final private static function isSameType(Enum $one, Enum $other): bool
    {
        return get_class($one) === get_class($other);
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isLess(Enum $one, Enum $other): bool
    {
        static::assertSameType($one, $other);

        return $one->getOrdinal() < $other->getOrdinal();
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @throws InvalidArgumentException
     */
    final private static function assertSameType(Enum $one, Enum $other)
    {
        if (!static::isSameType($one, $other)) {
            throw new InvalidArgumentException(sprintf(self::ERROR_COMPARING_TO_OTHER_TYPE, __METHOD__));
        }
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isLessOrEqual(Enum $one, Enum $other): bool
    {
        static::assertSameType($one, $other);

        return $one->getOrdinal() <= $other->getOrdinal();
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isGreater(Enum $one, Enum $other): bool
    {
        static::assertSameType($one, $other);

        return $one->getOrdinal() > $other->getOrdinal();
    }

    /**
     * @param Enum $one
     * @param Enum $other
     *
     * @return bool
     */
    final public static function isGreaterOrEqual(Enum $one, Enum $other): bool
    {
        static::assertSameType($one, $other);

        return $one->getOrdinal() >= $other->getOrdinal();
    }

    /**
     * @param Enum $enum
     *
     * @return string
     */
    final public static function toString(Enum $enum): string
    {
        return $enum->getName();
    }

    /**
     * @param Enum $enum
     *
     * @return string
     */
    final public static function toJson(Enum $enum): string
    {
        return json_encode(
            [
                self::FIELD_TYPE => get_class($enum),
                self::FIELD_NAME => $enum->getName(),
                self::FIELD_ORDINAL => $enum->getOrdinal(),
            ],
            JSON_PRETTY_PRINT
        );
    }
}
