<?php

namespace dnl_blkv\enum;

/**
 */
abstract class EnumLib
{
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
}
