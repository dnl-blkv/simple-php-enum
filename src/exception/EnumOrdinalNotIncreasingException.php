<?php

namespace dnl_blkv\enum\exception;

use Exception;

/**
 * @author Daniil Belyakov <daniil@bunq.com>
 * @since 20170326 Initial creation.
 */
class EnumOrdinalNotIncreasingException extends Exception
{
    /**
     * Error constants.
     */
    const ERROR_ENUM_ORDINAL_NOT_INCREASING = 'Last ordinal value "%s" is greater or equal then current "%s".';

    /**
     * @param int $ordinalLast
     * @param int $ordinalCurrent
     */
    public function __construct(int $ordinalLast, int $ordinalCurrent)
    {
        parent::__construct(sprintf(self::ERROR_ENUM_ORDINAL_NOT_INCREASING, $ordinalLast, $ordinalCurrent));
    }
}
