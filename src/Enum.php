<?php
namespace dnl_blkv\enum;

use Exception;

/**
 */
interface Enum
{
    /**
     * @param string $name
     *
     * @return static
     * @throws Exception If the name does not correspond to an existing enum type or arguments array is not empty.
     */
    public static function createFromName(string $name);

    /**
     * @param int $ordinal
     *
     * @return static
     * @throws Exception If the EnumBase ordinal is undefined.
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
}
