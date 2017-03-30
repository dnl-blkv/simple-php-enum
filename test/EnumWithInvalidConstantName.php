<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\AbstractEnum;

/**
 * @method static static VALID_NAME()
 * @method static static _INVALID_NAME()
 */
class EnumWithInvalidConstantName extends AbstractEnum
{
    const VALID_NAME = 0;
    const _INVALID_NAME = 1;
}
