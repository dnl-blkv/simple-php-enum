<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;

/**
 * @method static static VALID_NAME()
 * @method static static _IGNORED_NAME()
 */
class IgnoredConstantNameEnum extends Enum
{
    const VALID_NAME = 0;
    const _IGNORED_NAME = 1;
}
