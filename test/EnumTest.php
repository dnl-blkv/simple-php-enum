<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\EnumInterface;
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
     */
    public function testCanCreateEnumWithMagicMethod()
    {
        static::assertEquals(SimpleEnum::class, get_class(SimpleEnum::CAT()));
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumNameException
     */
    public function testCanNotCreateEnumWithMagicMethodForUndefinedConstant()
    {
        SimpleEnum::SCAT();
    }

    /**
     */
    public function testCanCreateEnumFromName()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::createFromName('DOG'));
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumNameException
     */
    public function testCanNotCreateEnumFromUndefinedName()
    {
        SimpleEnum::createFromName('SCAT');
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumNameException
     */
    public function testCanNotCreateEnumFromNameOfInternalConstant()
    {
        SimpleEnum::createFromName('__SOME_INTERNAL_CONSTANT');
    }

    /**
     */
    public function testCanCreateEnumFromOrdinal()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::createFromOrdinal(1));
    }

    /**
     */
    public function testCanCreateEnumFromCustomOrdinal()
    {
        static::assertEquals(SimpleEnum::BIRD(), SimpleEnum::createFromOrdinal(3));
    }

    /**
     * @expectedException \dnl_blkv\enum\exception\UndefinedEnumOrdinalException
     */
    public function testCanNotCreateEnumFromUndefinedOrdinal()
    {
        SimpleEnum::createFromOrdinal(20);
    }

    /**
     */
    public function testCanCheckEnumNameIsDefined()
    {
        self::assertTrue(SimpleEnum::isNameDefined('FISH'));
    }

    /**
     */
    public function testCanCheckEnumNameIsNotDefined()
    {
        self::assertFalse(SimpleEnum::isNameDefined('GISH'));
        self::assertFalse(SimpleEnum::isNameDefined('__SOME_INTERNAL_CONSTANT'));
    }

    /**
     */
    public function testCanGetName()
    {
        static::assertEquals('FISH', SimpleEnum::FISH()->getName());
    }

    /**
     */
    public function testCanCheckEnumOrdinalIsDefined()
    {
        self::assertTrue(SimpleEnum::isOrdinalDefined(1));
    }

    /**
     */
    public function testCanCheckEnumOrdinalIsNotDefined()
    {
        self::assertFalse(SimpleEnum::isOrdinalDefined(5));
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
        static::assertEquals(self::EXPECTED_RESULT_SIMPLE_ENUM_FISH_AS_STRING, strval(SimpleEnum::FISH()));
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
     * @expectedException \dnl_blkv\enum\exception\InvalidEnumNameException
     */
    public function testCanNotCreateEnumIfWrongNamePresent()
    {
        EnumWithInvalidConstantName::VALID_NAME();
    }

    /**
     * @param EnumInterface $one
     * @param EnumInterface $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsEqualCheck
     */
    public function testCanCheckIsEqual(EnumInterface $one, EnumInterface $other, bool $resultExpected)
    {
        self::assertEquals($resultExpected, $one->isEqual($other));
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
            [EnumWithDuplicatedOrdinal::CAT(), EnumWithDuplicatedOrdinal::DEFAULT(), true],
        ];
    }

    /**
     * @param EnumInterface $one
     * @param EnumInterface $other
     * @param bool $resultExpected
     *
     * @dataProvider enumPairProviderIsSameCheck
     */
    public function testCanCheckIsSame(EnumInterface $one, EnumInterface $other, bool $resultExpected)
    {
        self::assertEquals($resultExpected, $one->isSame($other));
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
            [EnumWithDuplicatedOrdinal::CAT(), EnumWithDuplicatedOrdinal::DEFAULT(), false],
        ];
    }

    /**
     */
    public static function testCanCreateFromDuplicateOrdinal()
    {
        $defaultEnumOrdinal = EnumWithDuplicatedOrdinal::DEFAULT()->getOrdinal();
        $catEnum = OtherSimpleEnum::createFromOrdinal($defaultEnumOrdinal);

        self::assertTrue(OtherSimpleEnum::CAT()->isSame($catEnum));
    }
}
