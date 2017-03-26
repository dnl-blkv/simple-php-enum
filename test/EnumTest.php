<?php
namespace dnl_blkv\enum\test;

use PHPUnit\Framework\TestCase;
use Exception;
use Closure;

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
     */
    public function testCanNotCreateEnumWithMagicMethodForUndefinedConstant()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::SCAT();
            },
            'Undefined enum name for "dnl_blkv\enum\test\SimpleEnum": "SCAT".'
        );
    }

    /**
     * @param Closure $function
     * @param string $errorMessageExpected
     */
    private static function assertTriggersException(Closure $function, string $errorMessageExpected)
    {
        $errorMessageActual = '';

        try {
            $result = $function();
        } catch (Exception $e) {
            $result = null;
            $errorMessageActual = $e->getMessage();
        }

        static::assertEquals($result, null);
        static::assertEquals($errorMessageExpected, $errorMessageActual);
    }

    /**
     */
    public function testCanNotCreateEnumWithMagicMethodForInternalConstant()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::__SOME_INTERNAL_CONSTANT();
            },
            'Undefined enum name for "dnl_blkv\enum\test\SimpleEnum": "__SOME_INTERNAL_CONSTANT".'
        );
    }

    /**
     */
    public function testCanCreateEnumFromName()
    {
        static::assertEquals(SimpleEnum::DOG(), SimpleEnum::createFromName('DOG'));
    }

    /**
     */
    public function testCanNotCreateEnumFromUndefinedName()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::createFromName('SCAT');
            },
            'Undefined enum name for "dnl_blkv\enum\test\SimpleEnum": "SCAT".'
        );
    }

    /**
     */
    public function testCanNotCreateEnumFromNameOfInternalConstant()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::createFromName('__SOME_INTERNAL_CONSTANT');
            },
            'Undefined enum name for "dnl_blkv\enum\test\SimpleEnum": "__SOME_INTERNAL_CONSTANT".'
        );
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
     */
    public function testCanNotCreateEnumFromUndefinedOrdinal()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::createFromOrdinal(20);
            },
            'Undefined enum ordinal for "dnl_blkv\enum\test\SimpleEnum": "20".'
        );
    }

    /**
     */
    public function testCanNotCreateEnumFromOrdinalOfInternalConstant()
    {
        static::assertTriggersException(
            function () {
                return SimpleEnum::createFromOrdinal(222);
            },
            'Undefined enum ordinal for "dnl_blkv\enum\test\SimpleEnum": "222".'
        );
    }

    /**
     */
    public function testCanCheckEnumNameIsDefined()
    {
        self::assertEquals(true, SimpleEnum::isNameDefined('FISH'));
    }

    /**
     */
    public function testCanCheckEnumNameIsNotDefined()
    {
        self::assertEquals(false, SimpleEnum::isNameDefined('GISH'));
        self::assertEquals(false, SimpleEnum::isNameDefined('__SOME_INTERNAL_CONSTANT'));
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
        self::assertEquals(true, SimpleEnum::isOrdinalDefined(1));
    }

    /**
     */
    public function testCanCheckEnumOrdinalIsNotDefined()
    {
        self::assertEquals(false, SimpleEnum::isOrdinalDefined(5));
        self::assertEquals(false, SimpleEnum::isOrdinalDefined(222));
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
