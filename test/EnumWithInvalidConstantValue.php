<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\EnumBase;

/**
 * @method static static VALID_VALUE()
 * @method static static INVALID_VALUE()
 */
class EnumWithInvalidConstantValue extends EnumBase
{
    const VALID_VALUE = 0;
    const INVALID_VALUE = -1;
}
