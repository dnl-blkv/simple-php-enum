<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;

/**
 * @method static static CAT()
 * @method static static DOG()
 * @method static static BIRD()
 * @method static static FISH()
 * @method static static DEFAULT()
 */
class DuplicatedOrdinalEnum extends Enum
{
    const CAT = null;
    const DOG = null;
    const BIRD = 3;
    const FISH = null;
    const DEFAULT = 0;
}
