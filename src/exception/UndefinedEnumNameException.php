<?php

namespace dnl_blkv\enum\exception;

use Exception;

/**
 * @author Daniil Belyakov <daniil@bunq.com>
 * @since 20170326 Initial creation.
 */
class UndefinedEnumNameException extends Exception
{
    /**
     * Error constants.
     */
    const ERROR_ENUM_NAME_UNDEFINED = 'Undefined enum name for "%s": "%s".';

    /**
     * @param string $className
     * @param string $name
     */
    public function __construct(string $className, string $name)
    {
        parent::__construct(sprintf(self::ERROR_ENUM_NAME_UNDEFINED, $className, $name));
    }
}
