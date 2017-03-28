<?php
namespace dnl_blkv\enum;

use dnl_blkv\enum\exception\UndefinedEnumNameException;
use dnl_blkv\enum\exception\UndefinedEnumOrdinalException;

/**
 */
interface Enum
{
    /**
     * @param string $name
     *
     * @return static
     * @throws UndefinedEnumNameException When the name does not correspond to an existing enum type.
     */
    public static function createFromName(string $name);

    /**
     * @param int $ordinal
     *
     * @return static
     * @throws UndefinedEnumOrdinalException When the enum ordinal is undefined.
     */
    public static function createFromOrdinal(int $ordinal);

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function isNameDefined(string $name): bool;

    /**
     * @param int $ordinal
     *
     * @return bool
     */
    public static function isOrdinalDefined(int $ordinal): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getOrdinal(): int;

    /**
     * Enums are equal if they have the same class and ordinal value.
     *
     * @param Enum $other
     *
     * @return bool
     */
    public function isEqual(Enum $other): bool;

    /**
     * Enums are same if they have the same class, ordinal value and name.
     *
     * @param Enum $other
     *
     * @return bool
     */
    public function isSame(Enum $other): bool;
}
