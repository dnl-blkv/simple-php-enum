# Simple PHP Enum
## Description
A simple C/C++ alike PHP library for Enums.

## Stats and Health
[![Travis CI](https://travis-ci.org/dnl-blkv/simple-php-enum.svg?branch=master)](https://travis-ci.org/dnl-blkv/simple-php-enum)
[![codecov](https://codecov.io/gh/dnl-blkv/simple-php-enum/branch/master/graph/badge.svg)](https://codecov.io/gh/dnl-blkv/simple-php-enum)

## PHP Version Support
The package is new and thus only supports PHP `>=7.0`.

# How To Basic
## Installation
1. [Install composer](https://getcomposer.org/doc/00-intro.md)
2. Open your project folder in terminal
3. Enter `composer require dnl-blkv/simple-php-enum`
4. Wait for the composer to finish the job
5. Now, you can start using the Simple PHP Enums as described below!

## Defining Enums
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

Here `null` means auto-determined ordinal value, or _auto-ordinal_. The default auto-ordinal is `0`. The further auto-ordinal values are determined as `{previous ordinal} + 1`.

Constant names MUST be [PSR-1-compliant](http://www.php-fig.org/psr/psr-1/) AND start from a capital letter. If the constant name does not conform with the rules, an `InvalidEnumNameException` is thrown.

## Creating
Once the class is defined defined, the enums can be created as:
```
$animal = AnimalEnum::CAT();
```

Or:
```
$animal = AnimalEnum::createFromName('CAT');
```

Or:
```
$animal = AnimalEnum::createFromOrdinal(0);
```

In the examples above, if the name or the ordinal is not defined, exceptions will be thrown (`UndefinedEnumNameException` and `UndefinedEnumOrdinalException` correspondingly).

## Accessing
You can access the name (string representation) and the ordinal (numeric representation) of the enum:
```
echo $animal->getName() . ' ' . $animal->getOrdinal(); // Outputs "CAT 0"
```

## Comparison
The enums can be compared as such:
```
$cat = AnimalEnum::CAT();
$otherCat = AnimalEnum::CAT();
$dog = AnimalEnum::DOG();
var_dump($cat->isEqual($otherCat)) // Outputs "bool(true)"
var_dump($cat->isEqual($dog)) // Outputs "bool(false)"
```
Intuitively, two enums of different types are never equal. If we have an enum of type `SomeOtherEnum` with `const VALUE = 0;` then the following holds:
```
var_dump(SomeOtherEnum::VALUE()->isEqual(AnimalEnum::CAT())) // Outputs "bool(false)"
```

# How To Advanced
## Defining Enums with Custom Ordinals
Besides letting the library assign the ordinals automatically, you could manually assign custom integer values to the ordinals:
```
use dnl_blkv\enum\AbstractEnum;

/**
 * @method static static PIZZA()
 * @method static static SUSHI()
 * @method static static KEBAB()
 * @method static static SALAD()
 */
class FoodEnum extends AbstractEnum
{
    const PIZZA = 5;
    const SUSHI = null;
    const KEBAB = 8;
    const SALAD = 10;
}
```
In this case the enums will be defined as following:
```
echo FoodEnum::PIZZA()->getOrdinal() . PHP_EOL; // Outputs "5"
echo FoodEnum::SUSHI()->getOrdinal() . PHP_EOL; // Outputs "6"
echo FoodEnum::KEBAB()->getOrdinal() . PHP_EOL; // Outputs "8"
echo FoodEnum::SALAD()->getOrdinal() . PHP_EOL; // Outputs "10"
```

## Duplicate Ordinals
Similarly to the vanilla C/C++ enums, this Simple PHP Enums allow for duplicate ordinals. This may be used for tackling such cases as a default value:
```
use dnl_blkv\enum\AbstractEnum;

/**
 * @method static static LAGER()
 * @method static static IPA()
 * @method static static PORTER()
 * @method static static STOUT()
 * @method static static DEFAULT()
 * @method static static AFTER_DEFAULT()
 */
class BeerEnum extends AbstractEnum
{
    const LAGER = 0;
    const IPA = null;
    const PORTER = null;
    const STOUT = null;
    const DEFAULT = 0;
    const AFTER_DEFAULT = null;
}
```

For the enum defined above, the following will hold:
```
echo BeerEnum::DEFAULT()->getOrdinal() . PHP_EOL; // Outputs "0"
echo BeerEnum::AFTER_DEFAULT()->getOrdinal() . PHP_EOL; // Outputs "1"
```

If you are creating an enum with duplicate ordinal using a magic method or from name, it works as usual.
```
echo BeerEnum::DEFAULT()->getName() . PHP_EOL; // Outputs "DEFAULT"
echo BeerEnum::createFromName('DEFAULT')->getName() . PHP_EOL; // Outputs "DEFAULT"
```

However, if you create it from an ordinal, the behavior may seem tricky at a glance:
```
echo BeerEnum::createFromOrdinal(0)->getName() . PHP_EOL; // Outputs "LAGER"
```

This happens because, when creating an enum from ordinal, the library always provides you with the first (in the order of definition) name corresponding to the ordinal.

## More Comparison
The Simple PHP Enum library only creates each enum object once and then reuses it. Therefore, the enums are comparable with `===` or its alias `isSame`. This kind comparison is stricter than `isEqual`. Whereas `isEqual` only accounts for the enum type and ordinal, `isSame` also takes the `name` into account:
```
var_dump(BeerEnum::LAGER()->isEqual(BeerEnum::LAGER())); // Outputs "bool(true)"
var_dump(BeerEnum::LAGER()->isEqual(BeerEnum::DEFAULT())); // Outputs "bool(true)"
var_dump(BeerEnum::LAGER() === BeerEnum::LAGER()); // Outputs "bool(true)"
var_dump(BeerEnum::LAGER() === BeerEnum::DEFAULT()); // Outputs "bool(false)"
var_dump(BeerEnum::LAGER()->isSame(BeerEnum::LAGER())); // Outputs "bool(true)"
var_dump(BeerEnum::LAGER()->isSame(BeerEnum::DEFAULT())); // Outputs "bool(false)"
```

## Checking Existence of Names and Ordinals
If you wish to check whether or not certain enum type has a name or an ordinal, there are methods allowing you to easily do so:
```
var_dump(BeerEnum::isNameDefined('STOUT')) // Outputs "bool(true)";
var_dump(BeerEnum::isNameDefined('VODKA')) // Outputs "bool(false)";
var_dump(BeerEnum::isOrdinalDefined(3)) // Outputs "bool(true)";
var_dump(BeerEnum::isOrdinalDefined(420)) // Outputs "bool(false)";
```

## Converting to String
The enums have an embedded magical mechanism for serialization:
```
echo BeerEnum::IPA() . PHP_EOL;

/* 
 * Outputs:
 * {
 *     "\your\name\space\BeerEnum": {
 *         "IPA": 1
 *      }
 * }
 */
```

# Notes
## Extension
All the internals of the `AbstractEnum` class are either `public` or `protected`. Therefore, it is completely open for extension and allows you to build your own, more complex constructions on top of it.

## Use with Databases
If you opt to use these enums with databases and store the ordinals, I would recommend to make sure that no stored enum has duplicate ordinals. Otherwise, it could happen that you store `DEFAULT = 0`, but receive `IPA = 0` upon recreation.
