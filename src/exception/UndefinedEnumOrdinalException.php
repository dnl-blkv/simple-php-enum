<?php

namespace dnl_blkv\enum\exception;

use Exception;

/**
 * @author Daniil Belyakov <daniil@bunq.com>
 * @since 20170326 Initial creation.
 */
class UndefinedEnumOrdinalException extends Exception
{
    /**
     * Error constants.
     */
    const ERROR_ENUM_ORDINAL_UNDEFINED = 'Undefined enum ordinal for "%s": "%d".';

    /**
     * @param string $className
     * @param int $ordinal
     */
    public function __construct(string $className, int $ordinal)
    {
        parent::__construct(sprintf(self::ERROR_ENUM_ORDINAL_UNDEFINED, $className, $ordinal));
    }
}
