<?php
namespace dnl_blkv\enum\test;

use PHPUnit\Framework\TestCase;
use Exception;

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
     * @expectedException Exception
     */
    public function testCanNotCreateEnumWithMagicMethodForUndefinedConstant()
    {
        SimpleEnum::SCAT();
    }

    /**
     * @expectedException Exception
     */
    public function testCanNotCreateEnumWithMagicMethodForInternalConstant()
    {
        SimpleEnum::__SOME_INTERNAL_CONSTANT();
    }

    /**
     */
    public function testCanCreateEnumFromName()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::createFromName('DOG'));
    }

    /**
     * @expectedException Exception
     */
    public function testCanNotCreateEnumFromUndefinedName()
    {
        SimpleEnum::createFromName('SCAT');
    }

    /**
     * @expectedException Exception
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
     * @expectedException Exception
     */
    public function testCanNotCreateEnumFromUndefinedOrdinal()
    {
        SimpleEnum::createFromOrdinal(20);
    }

    /**
     * @expectedException Exception
     */
    public function testCanNotCreateEnumFromOrdinalOfInternalConstant()
    {
        SimpleEnum::createFromOrdinal(222);
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
        self::assertFalse(SimpleEnum::isOrdinalDefined(222));
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
}
