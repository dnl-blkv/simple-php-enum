# Simple PHP Enum
## Description
A simple C/C++ alike PHP library for Enums.

## Stats and Health
[![Travis CI](https://travis-ci.org/dnl-blkv/simple-php-enum.svg?branch=master)](https://travis-ci.org/dnl-blkv/simple-php-enum)
[![codecov](https://codecov.io/gh/dnl-blkv/simple-php-enum/branch/master/graph/badge.svg)](https://codecov.io/gh/dnl-blkv/simple-php-enum)

## PHP Version Support
The package is new and thus only supports PHP ^7.0.

# How To Basic
## Defining
Defining a basic Enum with the package is straightforward:
```
use dnl_blkv\enum\AbstractEnum;

/**
 * @method static static CAT()
 * @method static static DOG()
 * @method static static BIRD()
 * @method static static FISH()
 */
class AnimalEnum extends AbstractEnum
{
    const CAT = null;
    const DOG = null;
    const BIRD = null;
    const FISH = null;
}
```

Here, `null` means _auto-determined_ ordinal value. The default auto-ordinal is `0`. The further auto-ordinal values are determined as `{previous ordinal} + 1`.

## Instantiating
Once defined, the Enum can be instantiated as:
```
$animal = AnimalEnum::CAT();
```

Or:
```
$animal = AnimalEnum::createFromName('CAT');
```

## Accessing
You can access the name (string representation) and the ordinal (numeric representation) of the enum:
```
echo $animal->getName() . ' ' . $animal->getOrdinal(); // Outputs "CAT 0"
```

## Comparison
The Simple Enums can be compared as such:
```
$cat = AnimalEnum::CAT();
$otherCat = AnimalEnum::CAT();
$dog = AnimalEnum::DOG();
var_dump($cat->isEqual($otherCat)) // Outputs "bool(true)"
var_dump($cat->isEqual($dog)) // Outputs "bool(false)"
```
