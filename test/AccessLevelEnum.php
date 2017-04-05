<?php

namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;

/**
 * @method static static READ()
 * @method static static WRITE()
 * @method static static ADMIN()
 */
class AccessLevelEnum extends Enum
{
    const READ = null;
    const WRITE = null;
    const ADMIN = null;
}
