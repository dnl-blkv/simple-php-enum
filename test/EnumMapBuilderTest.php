<?php
namespace dnl_blkv\enum\test;

use dnl_blkv\enum\EnumMapBuilder;
use PHPUnit\Framework\TestCase;
use Closure;

/**
 */
class EnumMapBuilderTest extends TestCase
{
    /**
     */
    public function testCanDefineNameToOrdinalMap()
    {
        $initializer = $this->createEnumMapBuilder(
            [
                'CAT' => null,
                'DOG' => null,
                'FISH'=> 1,
                'BIRD'=> null,
                'COW' => 4,
                '_IGNORED_ANIMAL' => null,
            ]
        );
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
     * @return EnumMapBuilder
     */
    private function createEnumMapBuilder(array $constants): EnumMapBuilder
    {
        return new EnumMapBuilder($constants, $this->getCreateEnumInstanceClosure());
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
}
