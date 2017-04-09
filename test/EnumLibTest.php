<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;
use dnl_blkv\enum\EnumLib;
use PHPUnit\Framework\TestCase;
use BadMethodCallException;
use InvalidArgumentException;

/**
 */
class EnumLibTest extends TestCase
{
    /**
     * @param string $constantName
     * @param bool $resultExpected
     *
     * @dataProvider enumConstantNameProviderIsValidConstantName
     */
    public function testCanCheckIsValidEnumConstantName(string $constantName, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isValidEnumConstantName($constantName));
    }

    /**
     * @return mixed[]
     */
    public static function enumConstantNameProviderIsValidConstantName()
    {
        return [
            ['VALID_CONSTANT_NAME', true],
            ['_INVALID_CONSTANT_NAME', false],
            ['iNVALID_CONSTANT_NAME', false],
            ['0INVALID_CONSTANT_NAME', false],
        ];
    }
}
