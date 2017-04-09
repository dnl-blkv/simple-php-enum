<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\Enum;
use PHPUnit\Framework\TestCase;
use BadMethodCallException;
use InvalidArgumentException;

/**
 */
class EnumTest extends TestCase
{
    /**
     * Expected result of converting SimpleEnum::FISH() to string.
     */
    const EXPECTED_RESULT_SIMPLE_ENUM_FISH_AS_STRING = <<<TEXT
{
    "dnl_blkv\\\\enum\\\\test\\\\SimpleEnum": {
        "FISH": 4
    }
}
TEXT;

    /**
     * @param string $name
     * @param bool $resultExpected
     *
     * @dataProvider enumNameProviderIsNameDefinedCheckWithIgnoredConstant
     */
    public function testCanCheckIsNameDefinedWithIgnoredConstant(string $name, bool $resultExpected)
    {
        static::assertEquals($resultExpected, IgnoredConstantNameEnum::isNameDefined($name));
    }

    /**
     * @return mixed[]
     */
    public static function enumNameProviderIsNameDefinedCheckWithIgnoredConstant()
    {
        return [
            ['VALID_NAME', true],
            ['INVALID_NAME', false],
            ['_IGNORED_NAME', false],
        ];
    }

    /**
     * @param int $ordinal
     * @param bool $resultExpected
     *
     * @dataProvider enumNameProviderIsOrdinalDefinedCheck
     */
    public function testCanCheckEnumOrdinalIsDefined(int $ordinal, bool $resultExpected)
    {
        static::assertEquals($resultExpected, SimpleEnum::isOrdinalDefined($ordinal));
    }

    /**
     * @return mixed[]
     */
    public static function enumNameProviderIsOrdinalDefinedCheck()
    {
        return [
            [1, true],
            [5, false],
        ];
    }

    /**
     */
    public function testCanGetEnumWithMagicMethod()
    {
        static::assertEquals(SimpleEnum::class, get_class(SimpleEnum::CAT()));
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumNameException
     */
    public function testCanNotGetEnumWithMagicMethodForUndefinedConstant()
    {
        SimpleEnum::SCAT();
    }

    /**
     */
    public function testCanGetEnumByName()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::getByName('DOG'));
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumNameException
     */
    public function testCanNotGetEnumByUndefinedName()
    {
        SimpleEnum::getByName('SCAT');
    }

    /**
     */
    public function testCanGetFirstEnumByOrdinal()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::getFirstByOrdinal(1));
    }

    /**
     */
    public function testCanGetAllEnumsByOrdinal()
    {
        static::assertEquals(
            [
                DuplicatedOrdinalEnum::CAT(),
                DuplicatedOrdinalEnum::DEFAULT(),
            ],
            DuplicatedOrdinalEnum::getAllByOrdinal(0)
        );
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumOrdinalException
     */
    public function testCanNotGetFirstEnumByUndefinedOrdinal()
    {
        SimpleEnum::getFirstByOrdinal(20);
    }

    /**
     */
    public function testCanGetName()
    {
        static::assertEquals('FISH', SimpleEnum::FISH()->getName());
    }

    /**
     */
    public function testCanGetOrdinal()
    {
        static::assertEquals(4, SimpleEnum::FISH()->getOrdinal());
    }

    /**
     */
    public function testCanConvertToString()
    {
        static::assertEquals(static::EXPECTED_RESULT_SIMPLE_ENUM_FISH_AS_STRING, strval(SimpleEnum::FISH()));
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testCanNotCallNonExistingMethod()
    {
        SimpleEnum::nonExistingMethod();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCanNotCallMagicEnumMethodWithArguments()
    {
        SimpleEnum::FISH(true);
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
        static::assertEquals($resultExpected, $one->isEqual($other));
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
        static::assertEquals($resultExpected, $one->isSame($other));
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
        static::assertEquals($resultExpected, $one->isLess($other));
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
        AccessLevelEnum::ADMIN()->isLess(SimpleEnum::CAT());
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
        static::assertEquals($resultExpected, $one->isLessOrEqual($other));
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
        AccessLevelEnum::ADMIN()->isLessOrEqual(SimpleEnum::CAT());
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
        static::assertEquals($resultExpected, $one->isGreater($other));
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
        AccessLevelEnum::ADMIN()->isGreater(SimpleEnum::CAT());
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
        static::assertEquals($resultExpected, $one->isGreaterOrEqual($other));
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
        AccessLevelEnum::ADMIN()->isGreaterOrEqual(SimpleEnum::CAT());
    }
}
