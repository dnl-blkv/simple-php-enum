<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\EnumBase;

/**
 * @method static static CAT()
 * @method static static DOG()
 * @method static static BIRD()
 * @method static static FISH()
 */
class SimpleEnum extends EnumBase
{
    const __SOME_INTERNAL_CONSTANT = 222;
    const CAT = true;
    const DOG = true;
    const BIRD = 3;
    const FISH = true;
    const __SOME_OTHER_INTERNAL_CONSTANT = 333;
}
