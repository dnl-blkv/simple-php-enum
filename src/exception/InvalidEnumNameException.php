<?php

namespace dnl_blkv\enum\exception;

use Exception;

/**
 * @author Daniil Belyakov <daniil@bunq.com>
 * @since 20170326 Initial creation.
 */
class InvalidEnumNameException extends Exception
{
    /**
     * Error constants.
     */
    const ERROR_ENUM_NAME_NOT_ALLOWED = 'Enum name is invalid: "%s".';

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf(self::ERROR_ENUM_NAME_NOT_ALLOWED, $name));
    }
}
