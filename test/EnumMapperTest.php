<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\EnumMapper;
use PHPUnit\Framework\TestCase;
use Closure;

/**
 */
class EnumMapperTest extends TestCase
{
    /**
     */
    public function testCanDefineNameToInstanceMap()
    {
        $initializer = $this->createEnumMapper($this->getEnumConstants());
        $resultExpected = [
            'CAT' => 'CAT|0',
            'DOG' => 'DOG|1',
            'FISH'=> 'FISH|1',
            'BIRD'=> 'BIRD|2',
            'COW' => 'COW|4',
        ];

        static::assertEquals($resultExpected, $initializer->getNameToInstanceMap());
    }

    /**
     * @param mixed[] $constants
     *
     * @return EnumMapper
     */
    private function createEnumMapper(array $constants): EnumMapper
    {
        return new EnumMapper($constants, $this->getCreateEnumInstanceClosure());
    }

    /**
     * @return Closure
     */
    private function getCreateEnumInstanceClosure(): Closure
    {
        return function (string $name, int $ordinal) {
            return $name . '|' . $ordinal;
        };
    }

    /**
     * @return mixed[]
     */
    private function getEnumConstants(): array
    {
        return [
            'CAT' => null,
            'DOG' => null,
            'FISH'=> 1,
            'BIRD'=> null,
            'COW' => 4,
            '_IGNORED_ANIMAL' => null,
        ];
    }

    /**
     */
    public function testCanDefineOrdinalToInstanceMap()
    {
        $enumMapper = $this->createEnumMapper($this->getEnumConstants());
        $resultExpected = [
            '0' => ['CAT|0'],
            '1' => ['DOG|1', 'FISH|1'],
            '2' => ['BIRD|2'],
            '4' => ['COW|4'],
        ];

        static::assertEquals($resultExpected, $enumMapper->getOrdinalToInstanceMap());
    }
}
