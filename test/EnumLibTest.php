<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;
use dnl_blkv\enum\EnumLib;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 */
class EnumLibTest extends TestCase
{
    /**
     * Expected result of serializing SimpleEnum::FISH().
     */
    const EXPECTED_RESULT_SIMPLE_ENUM_FISH_JSON = <<<TEXT
{
    "type": "dnl_blkv\\\\enum\\\\test\\\\SimpleEnum",
    "name": "FISH",
    "ordinal": 4
}
TEXT;

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

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsEqualCheck
     */
    public function testCanCheckIsEqual(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isEqual($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsEqualCheck()
    {
        return [
            [SimpleEnum::FISH(), SimpleEnum::FISH(), true],
            [SimpleEnum::FISH(), SimpleEnum::CAT(), false],
            [SimpleEnum::DOG(), OtherSimpleEnum::DOG(), false],
            [DuplicatedOrdinalEnum::CAT(), DuplicatedOrdinalEnum::DEFAULT(), true],
        ];
    }

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsSameCheck
     */
    public function testCanCheckIsSame(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isSame($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsSameCheck()
    {
        return [
            [SimpleEnum::FISH(), SimpleEnum::FISH(), true],
            [SimpleEnum::FISH(), SimpleEnum::CAT(), false],
            [SimpleEnum::DOG(), OtherSimpleEnum::DOG(), false],
            [DuplicatedOrdinalEnum::CAT(), DuplicatedOrdinalEnum::DEFAULT(), false],
        ];
    }

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsLessCheck
     */
    public function testCanCompareEnumsOfSameTypeUsingIsLess(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isLess($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsLessCheck()
    {
        return [
            [AccessLevelEnum::READ(), AccessLevelEnum::WRITE(), true],
            [AccessLevelEnum::READ(), AccessLevelEnum::READ(), false],
            [AccessLevelEnum::ADMIN(), AccessLevelEnum::WRITE(), false],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotCompareEnumsOfDifferentTypesUsingIsLess()
    {
        EnumLib::isLess(AccessLevelEnum::ADMIN(), SimpleEnum::CAT());
    }

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsLessOrEqualCheck
     */
    public function testCanCompareEnumsOfSameTypeUsingIsLessOrEqual(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isLessOrEqual($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsLessOrEqualCheck()
    {
        return [
            [AccessLevelEnum::READ(), AccessLevelEnum::WRITE(), true],
            [AccessLevelEnum::READ(), AccessLevelEnum::READ(), true],
            [AccessLevelEnum::ADMIN(), AccessLevelEnum::WRITE(), false],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotCompareEnumsOfDifferentTypesUsingIsLessOrEqual()
    {
        EnumLib::isLessOrEqual(AccessLevelEnum::ADMIN(), SimpleEnum::CAT());
    }

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsGreaterCheck
     */
    public function testCanCompareEnumsOfSameTypeUsingIsGreater(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isGreater($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsGreaterCheck()
    {
        return [
            [AccessLevelEnum::READ(), AccessLevelEnum::WRITE(), false],
            [AccessLevelEnum::READ(), AccessLevelEnum::READ(), false],
            [AccessLevelEnum::ADMIN(), AccessLevelEnum::WRITE(), true],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotCompareEnumsOfDifferentTypesUsingIsGreater()
    {
        EnumLib::isGreater(AccessLevelEnum::ADMIN(), SimpleEnum::CAT());
    }

    /**
     * @param Enum $one
     * @param Enum $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsGreaterOrEqualCheck
     */
    public function testCanCompareEnumsOfSameTypeUsingIsGreaterOrEqual(Enum $one, Enum $other, bool $resultExpected)
    {
        static::assertEquals($resultExpected, EnumLib::isGreaterOrEqual($one, $other));
    }

    /**
     * @return mixed[]
     */
    public static function enumPairProviderIsGreaterOrEqualCheck()
    {
        return [
            [AccessLevelEnum::READ(), AccessLevelEnum::WRITE(), false],
            [AccessLevelEnum::READ(), AccessLevelEnum::READ(), true],
            [AccessLevelEnum::ADMIN(), AccessLevelEnum::WRITE(), true],
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotCompareEnumsOfDifferentTypesUsingIsGreaterOrEqual()
    {
        EnumLib::isGreaterOrEqual(AccessLevelEnum::ADMIN(), SimpleEnum::CAT());
    }

    /**
     */
    public function testCanSerializeAsJson()
    {
        static::assertEquals(self::EXPECTED_RESULT_SIMPLE_ENUM_FISH_JSON, EnumLib::toJson(SimpleEnum::FISH()));
    }

    /**
     */
    public function testCanCovertToString()
    {
        static::assertEquals('FISH', EnumLib::toString(SimpleEnum::FISH()));
    }
}
